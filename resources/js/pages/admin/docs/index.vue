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
      <v-flex text-xs-right>
        <v-btn
          slot="activator"
          color="primary"
          dark
          @click="docNew"
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
      @runFilter="filterDocs"
    />
    <doctable-list
      v-if="!isLoading"
      :docs="docs"
      :statuses="statuses"
      :is-loading="isLoading || isLoadingList"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @paginate="onPaginate"
      @change-status="changeStatus"
      @manage-view="docView"
      @manage-edit="docEdit"
      @manage-delete="deleteDoc"
      @manage-multiple-delete="deleteDoc"
    />
    <v-dialog
      v-model="deleteDialog"
      max-width="500"
    >
      <simple-confirm
        :class="{ 'deleting': deletablesProcessing }"
        :title="deleteDialogTitle"
        ok-label="Yes"
        cancel-label="No"
        @ok="onSubmitDelete"
        @cancel="onCancelDelete"
      >
        <div class="py-5 px-2">
          <template v-if="deletablesProcessing">
            <div class="text-xs-center">
              <p class="subheading">
                Processing, please wait...
              </p>
              <v-progress-circular
                :rotate="-90"
                :size="200"
                :width="15"
                :value="deletablesProgress"
                color="primary"
              >
                {{ deletablesStatus }}
              </v-progress-circular>
            </div>
          </template>
          <template v-else>
            <p class="subheading">
              <span v-if="deletables.length < 2">Document</span>
              <span v-else> Documents</span>
              : {{ deleteTemp | formatDeleteTitles }}
            </p>
          </template>
        </div>
      </simple-confirm>
    </v-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex'
import docFilter from '~/components/docs/FilterSorter.vue'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import DoctableList from '~/components/docs/DoctableList.vue'
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import get from 'lodash/get'

export default {
  layout: 'admin',
  components: {
    docFilter,
    DoctableList,
    simpleConfirm
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.title).join(', ')
    }
  },
  mixins: [deletables],
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
    ...mapGetters('documentTypes', { 'types': 'items' }),
    ...mapGetters('documentStatuses', { 'statuses': 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('documents', ['sortables']),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this document?' : 'Are you sure you want to delete the following documents?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    docNew () {
      this.$router.push({ path: '/admin/docs/new' })
    },
    docView (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.uuid })
    },
    docEdit (doc) {
      this.$router.push({ path: '/admin/docs/' + doc.uuid })
    },
    deleteDoc (docs) {
      if (!Array.isArray(docs)) {
        docs = [docs]
      }
      this.deleteTemp = docs
      this.deleteDialog = true
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deleteTemp.forEach((doc) => {
        dispatcheables.push(this.$store.dispatch('documents/deleteItem', { getItems: false, params: { id: doc.uuid } }))
      })

      let chunks = this.chunk(dispatcheables, this.deleteTempParrallelRequest)
      let doneCount = 0

      for (let i in chunks) {
        await Promise.all(chunks[i])
        doneCount += chunks[i].length
        this.deleteTempStatus = doneCount + ' / ' + this.deleteTemp.length + ' Done'
        this.deleteTempProgress = doneCount / this.deleteTemp.length * 100
        await this.sleep(this.deletablesSleepTime)
      }

      this.filterDocs(this.lastFilterParams)
      await this.sleep(500)
      this.deletablesProcessing = false
      this.deleteDialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },
    changeStatus (status, doc) {
      this.$store.dispatch('documents/patchItem', { data: { status }, params: { id: doc.uuid } }).then(() => {
        this.filterDocs(this.lastFilterParams)
      })
    },
    onPaginate (value) {
      this.$store.dispatch('documents/setPagination', value)
      this.$store.dispatch('documents/getItems')
    },
    filterDocs (params) {
      this.lastFilterParams = params
      this.$store.dispatch('documents/setSort', params.sort)
      this.$store.dispatch('documents/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('documents/getItems')
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('documents/setFilters', {
      ...vm.$route.query
    })
    Promise.all([
      vm.$store.dispatch('documentStatuses/getItems'),
      vm.$store.dispatch('documentTypes/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
