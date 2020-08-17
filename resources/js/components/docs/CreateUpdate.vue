<template>
  <v-form
    v-if="!isLoading"
    ref="form"
    v-model="isValid"
  >
    <v-layout
      row
      align-center
      pt-3
    >
      <v-btn
        flat
        small
        @click="backToList"
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to Document list</span>
        </div>
      </v-btn>
    </v-layout>
    <v-layout
      row
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <v-select
          v-model="status"
          :items="statuses"
          single-line
          solo
          flat
          hide-details
        />
      </v-flex>
    </v-layout>
    <v-divider />
    <br>
    <v-layout
      row
      wrap
      pa-2
      justify-space-between
      class="doc-new-wrap"
    >
      <v-flex
        md7
        sm12
      >
        <v-card>
          <v-card-title class="subheading text-uppercase font-weight-bold">
            Basic information
          </v-card-title>
          <v-divider />
          <BasicInfoForm
            ref="basicInfo"
            :types="types"
            :templates="templates"
            :initdata="doc"
            :errors="errors"
            @data-change="changeBasicInfo"
          />
        </v-card>
      </v-flex>
      <v-flex
        md4
        sm12
      >
        <v-card>
          <v-card-title class="subheading text-uppercase font-weight-bold">
            Publishing
          </v-card-title>
          <v-divider />
          <PublishingForm
            :isvalid="isValid"
            :initdata="doc"
            @data-change="changePublishing"
            @data-save="onSaveClick"
          />
        </v-card>
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
import { omitBy, isNull } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import BasicInfoForm from '~/components/docs/BasicInfoForm.vue'
import PublishingForm from '~/components/docs/PublishingForm.vue'
import Validate from 'fresh-bus/components/mixins/Validate'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})

export default {
  layout: 'admin',
  components: {
    BasicInfoForm,
    PublishingForm
  },
  mixins: [Validate],
  data () {
    return {
      isNew: false,
      assigned_uuid: null,
      template: null,
      templates: [],
      file: { name: null, src: null }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('documents', { doc: 'item' }),
    ...mapGetters('documentTypes', { types: 'items' }),
    ...mapGetters('documentStatuses', { statuses: 'items' }),
    ...mapFields('documents', [
      'title',
      'type',
      'status',
      'expiration_at',
      'description',
      'assigned_type',
      'notes',
      'assigned',
      'event_store_uuid'
    ]),
    pageTitle () {
      return this.isNew ? 'New Document' : 'Document Details'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    changeBasicInfo (data) {
      this.title = data.title
      this.type = data.type
      this.description = data.description
      this.notes = data.notes
      this.template = data.template
      this.file = data.file
    },
    changePublishing (data) {
      this.doc.assigned_type = data.assigned_type
      this.doc.assigned_uuid = data.assigned_uuid
      this.doc.expiration_at = data.expiration_at
      this.doc.event_store_uuid = data.event_store_uuid
    },
    async validator () {
      const valids = await Promise.all([
        this.$validator.validateAll(),
        this.$refs.basicInfo.$validator.validateAll()
      ])
      return valids.every(valid => valid)
    },
    onSaveClick () {
      this.validator().then(async valid => {
        let data = { ...this.doc, assigned_uuid: this.assigned_uuid }
        data = omitBy(data, (value, key) => {
          const extra = ['created_at', 'updated_at', 'assigned', 'owner']
          return extra.includes(key) || isNull(value)
        })
        if (valid) {
          if (this.isNew) {
            data.id = 'new'
            await this.$store.dispatch('documents/createItem', { data })
            await this.$store.dispatch('documents/getItems')
            this.$router.push('/admin/docs/')
          } else {
            await this.$store.dispatch('documents/updateItem', {
              data,
              params: { id: data.uuid }
            })
            await this.$store.dispatch('generalMessage/setMessage', 'Saved')
          }
        }
      })
    },
    backToList () {
      this.$router.push({ path: '/admin/docs' })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id || 'new'
    Promise.all([
      vm.$store.dispatch('documents/getItem', { params: { id } }),
      vm.$store.dispatch('documentStatuses/getItems'),
      vm.$store.dispatch('documentTypes/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
      .catch((error) => { console.error(error) })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
      })
  }
}
</script>
<style scoped>
.doc-new-wrap {
  background-color: #fff;
}
.back-btn-inner {
  color: #fff;
  display: flex;
  align-items: center;
  font-size: 13px;
}
.back-btn-inner span {
  margin-left: 10px;
  font-weight: bold;
  text-transform: initial;
}
.back-btn-inner .v-icon {
  font-size: 16px;
}
</style>
