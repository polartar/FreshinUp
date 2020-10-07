<template>
  <div class="px-4">
    <v-flex
      d-flex
      align-center
      justify-space-between
    >
      <v-layout
        flex
        align-center
      >
        <h2 class="white--text mr-3">
          {{ pageTitle }}
        </h2>
        <v-btn
          slot="activator"
          color="white"
          href="/admin/doc-templates/new"
        >
          <span class="primary--text">Add New Template</span>
        </v-btn>
      </v-layout>
    </v-flex>
    <v-divider />
    <template>
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        @runFilter="filterItems"
      />
      <document-template-list
        :items="templates"
        :statuses="statuses"
        :is-loading="isLoadingList"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @paginate="onPaginate"
        @manage-view="onManageView"
        @manage-delete="deleteItem"
        @manage-multiple-delete="deleteItems"
        @change-status="changeStatusSingle"
        @change-status-multiple="changeStatusMultiple"
      />
    </template>
    <delete-dialog
      :value="deleteDialog"
      :progress="deletablesProgress"
      :progress-status="deletablesStatus"
      item-title-prop="title"
      :items="deleteTemp"
      @confirm="onSubmitDelete"
      @cancel="deleteDialog = false"
    />
  </div>
</template>
<script>
import get from 'lodash/get'
import { mapActions, mapGetters } from 'vuex'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import FilterSorter from '~/components/doc-templates/FilterSorter'
import DeleteDialog from '~/components/DeleteDialog'
import DocumentTemplateList from '~/components/doc-templates/DocumentTemplateList'

const INCLUDE = [
  'status'
]

export default {
  components: {
    FilterSorter,
    DeleteDialog,
    DocumentTemplateList
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.name).join(', ')
    }
  },
  extends: 'admin',
  mixins: [deletables],
  data () {
    return {
      deleteDialog: false,
      deleteTemp: [],
      deletablesProcessing: false,
      pageTitle: 'Document Templates List'
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.documentTemplates.pending.items', true)
    },
    ...mapGetters('documentTemplates', {
      templates: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy',
      sortables: 'sortables'
    }),
    ...mapGetters('documentTemplates/statuses', { statuses: 'items' }),
    ...mapGetters('page', {
      isLoading: 'isLoading'
    })
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onManageView (item) {
      this.$router.push({ path: `/admin/doc-templates/${item.uuid}/edit` })
    },
    deleteItem (item) {
      this.deleteTemp = [item]
      this.deleteDialog = true
    },
    deleteItems (items) {
      this.deleteTemp = items
      this.deleteDialog = true
    },
    changeStatusSingle (statusId, item) {
      this.$store.dispatch('documentTemplates/patchItem', {
        data: { status_id: statusId },
        params: { id: item.uuid }
      })
        .then(() => {
          this.$store.dispatch('documentTemplates/getItems')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    },
    changeStatusMultiple (statusId, items) {
      items.forEach((item) => {
        this.changeStatusSingle(statusId, item)
      })
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      let dispatcheables = []

      this.deleteTemp.forEach((item) => {
        dispatcheables.push(this.$store.dispatch('documentTemplates/deleteItem', {
          getItems: true,
          params: { id: item.uuid }
        }))
      })

      let chunks = this.chunk(dispatcheables, this.deleteTempParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.deletablesStatus = doneCount + ' / ' + this.deleteTemp.length + ' Done'
        this.deletablesProgress = doneCount / this.deleteTemp.length * 100
        await this.sleep(this.deletablesSleepTime)
      }

      await this.sleep(500)
      this.deletablesProcessing = false
      this.deleteDialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },
    onPaginate (value) {
      this.$store.dispatch('documentTemplates/setPagination', value)
      this.$store.dispatch('documentTemplates/getItems')
    },
    filterItems (params) {
      console.log(params)
      this.$store.dispatch('documentTemplates/setFilters', {
        ...this.$store.getters['documentTemplates/filters'],
        ...params
      })
      this.$store.dispatch('documentTemplates/getItems')
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('documentTemplates/setFilters', {
      include: INCLUDE
    })
    Promise.all([
      vm.$store.dispatch('documentTemplates/getItems'),
      vm.$store.dispatch('documentTemplates/statuses/getItems')
    ])
      .then(() => {
        if (next) next()
      })
      .catch(error => console.error(error))
      .then(() => {
        vm.setPageLoading(false)
      })
  }
}
</script>
