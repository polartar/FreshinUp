<template>
  <div>
    <v-flex
      d-flex
      align-center
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        {{ pageTitle }}
      </h2>
      <v-flex text-xs-right>å
        <v-btn
          slot="activator"
          color="primary"
          dark
          @click="docsNew"
        >
          Add New Content
        </v-btn>
      </v-flex>
    </v-flex>
    <v-divider />

    <br>
    <doc-filter
      v-if="!isLoading"
      :types="types"
      :statuses="statuses"
      :sortables="sortables"
      @runFilter="docsFilter"
    />
    <doctable-list
      v-if="!isLoading"
      :docs="docs"
      :statuses="statuses"
      :is-loading="isLoading || isLoadingList"
      :rows-per-page="docsPagination.rowsPerPage"
      :page="docsPagination.page"
      :total-items="docsPagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @paginate="docsOnPaginate"
      @change-status="docsChangeStatus"
      @manage-view="docsView"
      @manage-edit="docsEdit"
      @manage-delete="docsDelete"
      @manage-multiple-delete="docsDelete"
      @change_status_multiple="docsChangeStatusMultiple"
    />
    <docs-delete-dialog
      v-model="docsDeleteDialog"
      :delete-temp="docsDeleteTemp"
      :on-submit-delete="docsOnSubmitDelete"
      :on-cancel-delete="docsOnCancelDelete"
      :dialog-title="deleteDialogTitle"
    />
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex'
import DocFilter from '~/components/docs/FilterSorter.vue'
import DoctableList from '~/components/docs/DoctableList.vue'
import get from 'lodash/get'
import DocsDatatableManager from '~/components/mixins/DocsDatatableManager'
import DocsDeleteDialog from '~/components/docs/DeleteDialog.vue'

export default {
  layout: 'admin',
  components: {
    DocFilter,
    DoctableList,
    DocsDeleteDialog
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.title).join(', ')
    }
  },
  mixins: [DocsDatatableManager],
  data () {
    return {
      pageTitle: 'Document List',
      deleteDialog: false,
      lastFilterParams: {
        sort: '-created_at'
      },
      deleteTemp: []
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.documents.pending.items', true)
    },
    ...mapGetters('documents', {
      docs: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('documentTypes', { types: 'items' }),
    ...mapGetters('documentStatuses', { statuses: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('documents', ['sortables']),
    deleteDialogTitle () {
      return this.docsDeleteTemp.length < 2
        ? 'Are you sure you want to delete this document?'
        : 'Are you sure you want to delete the following documents?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('documents/setFilters', {
      ...vm.$route.query,
      assigned_uuid: vm.$store.getters.currentUser.uuid
    })
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('documentStatuses/getItems'),
      vm.$store.dispatch('documentTypes/getItems')
    ])
      .then(() => {
        if (next) next()
      })
      .catch(error => console.error(error))
      .then(() => vm.setPageLoading(false))
  }
}
</script>
