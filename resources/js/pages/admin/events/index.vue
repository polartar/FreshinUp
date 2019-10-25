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
          @click="eventNew"
        >
          <span class="primary--text">Add New Event</span>
        </v-btn>
      </v-layout>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <v-select
          v-model="view"
          :items="views"
          single-line
          solo
          flat
          hide-details
        />
      </v-flex>
    </v-flex>
    <v-divider />
    <filter-sorter
      v-if="!isLoading"
      :statuses="statuses"
      @runFilter="filterEvents"
    />
    <event-list
      v-if="!isLoading"
      :events="events"
      :statuses="statuses"
      :is-loading="isLoading || isLoadingList"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @paginate="onPaginate"
      @manage-edit="eventEdit"
      @manage-delete="deleteSingle"
      @manage-multiple-delete="multipleDelete"
      @change-status="changeStatusSingle"
      @change-status-multiple="changeStatusMultiple"
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
              <span v-if="deletables.length < 2">Event</span>
              <span v-else> Events</span>
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
import { mapGetters, mapActions, mapState } from 'vuex'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import FilterSorter from '~/components/events/FilterSorter.vue'
import EventList from '~/components/events/EventList.vue'
import SimpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
const include = [
  'status',
  'host',
  'location.venue',
  'manager',
  'event_tags'
]

export default {
  layout: 'admin',
  components: {
    EventList,
    FilterSorter,
    SimpleConfirm
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.title).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Events',
      deleteDialog: false,
      deleteTemp: [],
      deletablesProcessing: false,
      deletablesProgress: 0,
      deletablesStatus: '',
      lastFilterParams: {},
      view: 1,
      views: [
        { value: 1, text: 'List view' },
        { value: 2, text: 'Calendar view' }
      ]
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.events.pending.items', true)
    },
    ...mapGetters('events', {
      events: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('events', ['sortables']),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this event?' : 'Are you sure you want to delete the following events?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    eventNew () {
      this.$router.push({ path: '/admin/events/new' })
    },
    eventEdit (event) {
      this.$router.push({ path: '/admin/events/' + event.uuid })
    },
    deleteSingle (event) {
      this.deleteTemp = [event]
      this.deleteDialog = true
    },
    multipleDelete (events) {
      this.deleteTemp = events
      this.deleteDialog = true
    },
    changeStatusSingle (statusId, event) {
      this.$store.dispatch('events/patchItem', { data: { status_id: statusId }, params: { id: event.uuid } }).then(() => {
        this.filterEvents(this.lastFilterParams)
      })
    },
    changeStatusMultiple (statusId, events) {
      events.forEach((event) => {
        this.changeStatus(statusId, event)
      })
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deleteTemp.forEach((event) => {
        dispatcheables.push(this.$store.dispatch('events/deleteItem', { getItems: false, params: { id: event.uuid } }))
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
    onPaginate (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems', { params: { include: include } })
    },
    filterEvents (params) {
      this.lastFilterParams = params
      this.$store.dispatch('events/setSort', params.sort)
      this.$store.dispatch('events/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('events/getItems', { params: { include: include } })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query,
      ...this.lastFilterParams
    })
    Promise.all([
      vm.$store.dispatch('eventStatuses/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
