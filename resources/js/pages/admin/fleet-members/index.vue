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
          @click="storeNew"
        >
          Add New Fleet Member
        </v-btn>
      </v-flex>
    </v-flex>

    <search-filter-sorter
      without-filter-label
      without-sort-by
      @run="runFilter"
    />

    <store-list
      v-if="!isLoading"
      :stores="stores"
      :statuses="statuses"
      :is-loading="isLoading || isLoadingList"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @paginate="onPaginate"
      @change-status="changeStatus"
      @manage-view="storeView"
      @manage-edit="storeEdit"
      @manage-delete="deleteStore"
      @manage-multiple-delete="deleteStore"
      @change_status_multiple="changeStatusMultiple"
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
              <span v-if="deletables.length < 2">Store</span>
              <span v-else> Stores</span>
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
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import StoreList from '~/components/stores/StoreList.vue'
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import get from 'lodash/get'
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  layout: 'admin',
  components: {
    StoreList,
    simpleConfirm,
    SearchFilterSorter
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.name).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Fleet Member List',
      deleteDialog: false,
      lastFilterParams: {
        sort: '-created_at'
      },
      deleteTemp: []
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.stores.pending.items', true)
    },
    ...mapGetters('stores', {
      stores: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('stores', ['sortables']),
    ...mapGetters('storeStatuses', { 'statuses': 'items' }),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this store?' : 'Are you sure you want to delete the following stores?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    storeNew () {
      alert('coming soon')
    },
    storeView (store) {
      alert('coming soon')
    },
    storeEdit (store) {
      alert('coming soon')
    },
    deleteStore (stores) {
      if (!Array.isArray(stores)) {
        stores = [stores]
      }
      this.deleteTemp = stores
      this.deleteDialog = true
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deleteTemp.forEach((store) => {
        dispatcheables.push(this.$store.dispatch('stores/deleteItem', { getItems: false, params: { id: store.uuid } }))
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

      this.filterStores(this.lastFilterParams)
      await this.sleep(500)
      this.deletablesProcessing = false
      this.deleteDialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },
    changeStatus (status, store) {
      this.$store.dispatch('stores/patchItem', { data: { status }, params: { id: store.uuid } }).then(() => {
        this.filterStores(this.lastFilterParams)
      })
    },
    changeStatusMultiple (status, stores) {
      stores.forEach((store) => {
        this.changeStatus(status, store)
      })
    },
    onPaginate (value) {
      this.$store.dispatch('stores/setPagination', value)
      this.$store.dispatch('stores/getItems')
    },
    runFilter(params) {
      this.filterStores({
        name: params.term,
        ...this.lastFilterParams
      })
    },
    filterStores (params) {
      this.lastFilterParams = params
      this.$store.dispatch('stores/setSort', params.sort)
      this.$store.dispatch('stores/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('stores/getItems')
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)

    Promise.all([
      vm.$store.dispatch('stores/setFilters', {
        ...vm.$route.query
      }),
      vm.$store.dispatch('storeStatuses/getItems')
    ])
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>
