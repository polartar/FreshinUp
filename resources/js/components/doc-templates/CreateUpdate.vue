<template>
  <div>
    <v-layout
      pt4
      class="px-4"
      column
    >
      <v-flex>
        <v-btn
          flat
          small
          class="mx-0"
          @click="returnToList"
        >
          <div class="d-flex align-content-center white--text">
            <v-icon>fas fa-arrow-left</v-icon>
            <span
              class="mx-3"
              style="line-height: 1.60rem;"
            >Return to template list</span>
          </div>
        </v-btn>
      </v-flex>
      <v-flex class="d-flex justify-space-between align-content-center my-3">
        <div class="white--text headline">
          {{ pageTitle }}
        </div>
      </v-flex>
      <v-flex class="mt-5">
        <basic-information
          :is-loading="templateLoading"
          :value="template"
          :statuses="statuses"
          @input="onSave"
          @cancel="returnToList"
          @delete="deleteDialog = true"
        />
      </v-flex>
    </v-layout>
    <delete-dialog
      v-if="!isNew"
      :value="deleteDialog"
      title="All to trash ?"
      item-title-prop="title"
      :items="[template]"
      @confirm="onDelete"
      @cancel="deleteDialog = false"
    />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import BasicInformation, { DEFAULT_TEMPLATE } from './BasicInformation'
import get from 'lodash/get'
import DeleteDialog from '~/components/DeleteDialog'

const INCLUDE = [
  'status',
  'updated_by'
]
export default {
  layout: 'admin',
  components: {
    BasicInformation,
    DeleteDialog
  },
  data () {
    return {
      templateLoading: false,
      deleteDialog: false
    }
  },
  computed: {
    ...mapGetters('documentTemplates', { template_: 'item' }),
    ...mapGetters('documentTemplates/statuses', { statuses: 'items' }),
    isNew () {
      return get(this.$route, 'params.id', 'new') === 'new'
    },
    pageTitle () {
      return this.isNew ? 'New Document template' : 'Document template details'
    },
    template () {
      return this.isNew ? DEFAULT_TEMPLATE : this.template_
    }
  },
  methods: {
    get,
    returnToList () {
      this.$router.push({ path: '/admin/doc-templates' })
    },
    async onSave (data) {
      try {
        this.templateLoading = true
        if (this.isNew) {
          await this.$store.dispatch('documentTemplates/createItem', { data })
          await this.$store.dispatch('generalMessage/setMessage', 'Created.')
          this.returnToList()
        } else {
          await this.$store.dispatch('documentTemplates/updateItem', { data, params: { id: data.uuid } })
          await this.$store.dispatch('generalMessage/setMessage', 'Saved.')
        }
      } catch (error) {
        const message = get(error, 'response.data.message', error.message)
        this.$store.dispatch('generalErrorMessages/setErrors', message)
      } finally {
        this.templateLoading = false
      }
    },
    async onDelete (data) {
      await this.$store.dispatch('documentTemplates/deleteItem', { getItems: false, params: { id: data.uuid } })
      await this.$store.dispatch('generalMessage/setMessage', 'Deleted.')
      this.returnToList()
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id
    const promises = []
    if (id !== 'new') {
      vm.$store.dispatch('documentTemplates/setFilters', {
        include: INCLUDE
      })
      promises.push(vm.$store.dispatch('documentTemplates/getItem', {
        params: { id }
      }))
    }
    promises.push(vm.$store.dispatch('documentTemplates/statuses/getItems'))
    Promise.all(promises)
      .then(() => {})
      .catch(error => {
        console.error(error)
      })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        next && next()
      })
  }
}
</script>

<style scoped>

</style>
