<template>
  <v-form
    v-if="!isLoading"
    ref="form"
    v-model="isValid"
    lazy-validation
  >
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
import { omit } from 'lodash'
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
      assigned_uuid: null,
      pageTitle: 'Document Details',
      template: null,
      templates: [],
      file: { name: null, src: null }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('documents', { doc: 'item' }),
    ...mapGetters('documentTypes', { 'types': 'items' }),
    ...mapGetters('documentStatuses', { 'statuses': 'items' }),
    ...mapFields('documents', [
      'title',
      'type',
      'status',
      'expiration_at',
      'description',
      'assigned_type',
      'notes',
      'assigned'
    ])
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
      this.assigned_type = data.assigned_type
      this.assigned_uuid = data.assigned_uuid
      this.expiration_at = data.expiration_at
    },
    onSaveClick () {
      this.$validator.validate().then(valid => {
        let data = omit(this.doc, ['created_at', 'updated_at', 'assigned', 'owner'])
        data = { ...data, assigned_uuid: this.assigned_uuid }
        if (valid) {
          this.$store.dispatch('documents/updateItem', { data, params: { id: data.uuid } }).then(() => {
            this.$store.dispatch('generalMessage/setMessage', 'Saved')
          })
        }
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id
    Promise.all([
      vm.$store.dispatch('documents/getItem', { params: { id } }),
      vm.$store.dispatch('documentStatuses/getItems'),
      vm.$store.dispatch('documentTypes/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
<style scoped>
  .doc-new-wrap{
    background-color: #fff;
  }
</style>
