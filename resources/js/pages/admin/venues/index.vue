<template>
  <div>
    <v-flex
      d-flex
      align-center
      justify-space-between
      ma-3
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
          @click="onCreateNew"
        >
          <span class="primary--text">Add New Venue</span>
        </v-btn>
      </v-layout>
    </v-flex>
    <v-divider />
    <template>
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        @runFilter="filterVenues"
      />
      <venue-list
        :items="venues"
        :statuses="statuses"
        :is-loading="isLoading || isLoadingList"
        :rows-per-page="pagination.rowsPerPage"
        :page="pagination.page"
        :total-items="pagination.totalItems"
        :sort-by="sorting.sortBy"
        :descending="sorting.descending"
        @paginate="onPaginate"
        @manage-view="onManageView"
        @manage-delete="deleteSingle"
        @manage-multiple-delete="multipleDelete"
        @change-status="changeStatusSingle"
        @change-status-multiple="changeStatusMultiple"
      />
    </template>
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
              <span v-if="deletables.length < 2">Venue</span>
              <span v-else> Venues</span>
              : {{ deleteTemp | formatDeleteTitles }}
            </p>
          </template>
        </div>
      </simple-confirm>
    </v-dialog>
  </div>
</template>
<script>
import get from 'lodash/get'
import { mapActions, mapGetters, mapState } from 'vuex'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import VenueList from '~/components/venues/VenueList.vue'
import SimpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import FilterSorter from '~/components/venues/FilterSorter'

const INCLUDE = [
  'status',
  'owner',
  'locations'
]

export default {
  layout: 'admin',
  components: {
    VenueList,
    SimpleConfirm,
    FilterSorter
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.name).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Venues List',
      deleteDialog: false,
      deleteTemp: [],
      deletablesProcessing: false,
      deletablesProgress: 0,
      deletablesStatus: '',
      lastFilterParams: {}
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.venues.pending.items', true)
    },
    ...mapGetters(['currentUser']),
    ...mapGetters('venues', {
      venues: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('venueStatuses', { statuses: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('venues', ['sortables']),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this venue?' : 'Are you sure you want to delete the following venues?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onCreateNew () {
      this.$router.push({ path: '/admin/venues/new' })
    },
    onManageView (venue) {
      this.$router.push({ path: `/admin/venues/${venue.uuid}/edit` })
    },
    deleteSingle (venue) {
      this.deleteTemp = [venue]
      this.deleteDialog = true
    },
    multipleDelete (venues) {
      this.deleteTemp = venues
      this.deleteDialog = true
    },
    changeStatusSingle (statusId, venue) {
      this.$store.dispatch('venues/patchItem', {
        data: { status_id: statusId },
        params: { id: venue.uuid }
      }).then(() => {
        this.filterVenues(this.lastFilterParams)
      })
    },
    changeStatusMultiple (statusId, venues) {
      venues.forEach((venue) => {
        this.changeStatusSingle(statusId, venue)
      })
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deleteTemp.forEach((venue) => {
        dispatcheables.push(this.$store.dispatch('venues/deleteItem', {
          getItems: false,
          params: { id: venue.uuid }
        }))
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

      this.filterVenues(this.lastFilterParams)
      await this.sleep(500)
      this.deletablesProcessing = false
      this.deleteDialog = false
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    },
    onPaginate (value) {
      this.$store.dispatch('venues/setPagination', value)
      this.$store.dispatch('venues/getItems', { params: { include: INCLUDE } })
    },
    filterVenues (params) {
      this.lastFilterParams = params
      this.$store.dispatch('venues/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('venues/getItems', { params: { include: INCLUDE } })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('venues/setFilters', {
      ...vm.$route.query,
      ...this.lastFilterParams
    })
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('venueStatuses/getItems')
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
