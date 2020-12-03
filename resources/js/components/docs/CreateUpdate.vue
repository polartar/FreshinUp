<template>
  <div class="mx-4">
    <v-layout
      row
      align-center
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
          :value="doc.status_id"
          :items="statuses"
          single-line
          solo
          flat
          hide-details
          @input="changeStatus"
        />
      </v-flex>
    </v-layout>
    <basic-information
      ref="basicInfo"
      :is-loading="isLoading"
      :types="types"
      :templates="templates"
      :value="doc"
      @input="onSaveClick"
      @download="downloadDocument"
      @preview="previewDialog = true"
    />
    <v-dialog
      v-model="previewDialog"
      max-width="1200"
    >
      <document-preview
        :value="doc"
        :templates="templates"
        :variables="templateVariables"
        :events="events"
        @close="previewDialog = false"
      />
    </v-dialog>
  </div>
</template>

<script>
import { omitBy, isNull, get } from 'lodash'
import { mapGetters, mapActions } from 'vuex'
import BasicInformation, { DEFAULT_DOCUMENT } from '~/components/docs/BasicInformation.vue'
import DocumentPreview from '~/components/docs/DocumentPreview.vue'
import Validate from 'fresh-bus/components/mixins/Validate'

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    DocumentPreview
  },
  mixins: [Validate],
  data () {
    return {
      template: null,
      previewDialog: false,
      file: { name: null, src: null }
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('documents', { doc_: 'item' }),
    ...mapGetters('documentTypes', { types: 'items' }),
    ...mapGetters('documentStatuses', { statuses: 'items' }),
    ...mapGetters('documentTemplates', { templates: 'items' }),
    ...mapGetters('events', { events: 'items' }),
    isNew () {
      return get(this, '$route.params.id', 'new') === 'new'
    },
    pageTitle () {
      return this.isNew ? 'New Document' : 'Document Details'
    },
    doc () {
      return Object.assign({}, DEFAULT_DOCUMENT, this.doc_)
    },
    templateVariables () {
      // TODO: issue coming soon
      return {}
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    downloadDocument () {
      // TODO: see https://github.com/FreshinUp/foodfleet/issues/531
    },
    async validator () {
      const valids = await Promise.all([
        this.$validator.validateAll(),
        this.$refs.basicInfo.$validator.validateAll()
      ])
      return valids.every(valid => valid)
    },
    async onSaveClick (payload) {
      // const valid = await this.validator()
      // if (!valid) {
      //   this.$store.dispatch('generalErrorMessages/setErrors', 'Form is invalid.')
      //   return false
      // }
      const data = omitBy(payload, (value, key) => {
        const extra = ['created_at', 'updated_at', 'assigned', 'owner']
        return extra.includes(key) || isNull(value)
      })
      if (this.isNew) {
        await this.$store.dispatch('documents/createItem', { data })
        await this.$store.dispatch('generalMessage/setMessage', 'Created.')
        this.backToList()
      } else {
        await this.$store.dispatch('documents/updateItem', {
          data,
          params: { id: data.uuid }
        })
        await this.$store.dispatch('generalMessage/setMessage', 'Saved.')
      }
    },
    backToList () {
      this.$router.push({ path: '/admin/docs' })
    },
    changeStatus (status) {
      if (!this.isNew) {
        this.$store
          .dispatch('documents/patchItem', {
            data: { status_id: +status },
            params: { id: this.doc.uuid }
          })
      } else {
        this.status_id = status
      }
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const id = to.params.id || 'new'
    const promises = []
    if (id !== 'new') {
      promises.push(vm.$store.dispatch('documents/getItem', {
        params: { id, include: 'template' }
      }))
    }
    promises.push(vm.$store.dispatch('documentStatuses/getItems'))
    promises.push(vm.$store.dispatch('documentTypes/getItems'))
    promises.push(vm.$store.dispatch('events/getItems'))
    vm.$store.dispatch('documentTemplates/setFilters', {
      status_id: vm.$store.getters['documentTemplates/STATUS'].PUBLISHED
    })
    promises.push(vm.$store.dispatch('documentTemplates/getItems'))
    Promise.all(promises)
      .then(() => {})
      .catch((error) => { console.error(error) })
      .then(() => {
        if (next) next()
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
