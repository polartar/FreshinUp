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
    <template
      v-if="view === 1"
    >
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        :types="eventTypes"
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
        @manage-duplicate="eventDuplicate"
        @manage-delete="deleteSingle"
        @manage-multiple-delete="multipleDelete"
        @change-status="changeStatusSingle"
        @change-status-multiple="changeStatusMultiple"
      />
    </template>
    <v-layout
      v-else-if="view === 2"
      row
      flex
    >
      <v-flex
        sm3
        xs12
        ml-3
        mt-3
      >
        <v-card>
          <filter-sorter-for-calendar
            v-if="!isLoading"
            :statuses="statuses"
            @runFilter="filterEvents"
          />
        </v-card>
      </v-flex>
      <v-flex
        sm9
        xs12
        ma-3
      >
        <v-card>
          <event-calendar
            v-if="!isLoading"
            :events="events"
            :date="calendarDefaultDate"
            :year-range="calendarYearRange"
            @click-event="eventEdit"
          />
        </v-card>
      </v-flex>
    </v-layout>
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
    <v-flex>
      <v-dialog
        v-model="show"
        max-width="500"
      >
        <duplicate-dialog
          :loading="loading"
          :show="show"
          @Duplicate="onDuplicate"
          @manage-duplicate-dialog="changeDuplicateDialogue"
        />
      </v-dialog>
    </v-flex>
  </div>
</template>
<script>
import get from 'lodash/get'
import moment from 'moment'
import { mapActions, mapGetters, mapState } from 'vuex'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import FilterSorter from '~/components/events/FilterSorter.vue'
import FilterSorterForCalendar from '~/components/events/FilterSorterForCalendar.vue'
import EventList from '~/components/events/EventList.vue'
import EventCalendar from '~/components/events/EventCalendar.vue'
import SimpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
import DuplicateDialog from '~/components/events/DuplicateDialog.vue'
import getFileNameCopy from '~/components/events/utils.js'

const INCLUDE = [
  'status',
  'host',
  'location',
  'manager',
  'type',
  'venue'
]

export default {
  layout: 'admin',
  components: {
    EventList,
    EventCalendar,
    FilterSorter,
    FilterSorterForCalendar,
    SimpleConfirm,
    DuplicateDialog
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.name).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    const currentMoment = moment()
    return {
      pageTitle: 'Events',
      deleteDialog: false,
      show: false,
      loading: false,
      deleteTemp: [],
      deletablesProcessing: false,
      deletablesProgress: 0,
      deletablesStatus: '',
      lastFilterParams: {},
      calendarDefaultDate: currentMoment.format('YYYY-MM-DD'),
      calendarYearRange: [currentMoment.year() - 2, currentMoment.year() + 2],
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
    ...mapGetters(['currentUser']),
    ...mapGetters('events', {
      events: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('eventTypes', { 'eventTypes': 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('events', ['sortables']),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this event?' : 'Are you sure you want to delete the following events?'
    },
    role () {
      if (this.currentUser.isAdmin) return 'admin'
      if (this.currentUser.type === 1) return 'supplier'
      if (this.currentUser.type === 2) return 'host'
      return 'admin'
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
      this.$router.push({ path: '/admin/events/' + event.uuid + '/edit' })
    },
    deleteSingle (event) {
      this.deleteTemp = [event]
      this.deleteDialog = true
    },
    changeDuplicateDialogue (value) {
      this.show = value
    },
    async eventDuplicate (event) {
      this.show = true
      const params = {
        id: event.uuid,
        include: 'manager,host,event_tags,venue,location'
      }
      await this.$store.dispatch('events/getItem', { params })
        .then()
        .catch(error => {
          console.error(error)
        })
        .then()
    },
    onDuplicate (duplicate) {
      const toDuplicate = [
        {
          condition: duplicate.basicInformation,
          action: (payload) => {
            return {
              ...payload,
              ...this.event
            }
          }
        },
        {
          condition: duplicate.venue,
          action: (payload) => {
            return {
              ...payload
            }
          }
        },
        {
          condition: duplicate.fleetMember,
          action: (payload) => {
            return {
              ...payload
            }
          }
        },
        {
          condition: duplicate.customer,
          action: (payload) => {
            return {
              ...payload
            }
          }
        }
      ]

      const data = toDuplicate.reduce((payload, strategy) => {
        if (strategy.condition) {
          payload = Object.assign({}, payload, strategy.action(payload))
        }
        return payload
      }, {})

      if (data.name) {
        data.name = getFileNameCopy(data.name)
      }
      this.loading = true
      this.$store.dispatch('events/createItem', {
        data: {
          attendees: data.attendees,
          budget: data.budget,
          commission_rate: data.commission_rate,
          commission_type: data.commission_type,
          customer_notes: data.customer_notes,
          end_at: data.end_at,
          host_status: data.host_status,
          host_uuid: data.host_uuid,
          manager_uuid: data.manager_uuid,
          member_notes: data.member_notes,
          name: data.name,
          schedule: null,
          staff_notes: data.staff_notes,
          start_at: data.start_at,
          status_id: data.status_id,
          type_id: data.type_id
        }
      })
        .then(response => {
          const eventUuid = get(response, 'data.data.uuid')
          if (eventUuid) {
            const path = `/admin/events/${eventUuid}/edit`
            this.$router.push({ path })
            this.$store.dispatch('generalMessage/setMessage', 'Saved.')
          }
        })
        .catch(error => {
          console.error(error)
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.loading = false
          this.show = false
        })
    },
    multipleDelete (events) {
      this.deleteTemp = events
      this.deleteDialog = true
    },
    changeStatusSingle (statusId, event) {
      this.$store.dispatch('events/patchItem', {
        data: { status_id: statusId },
        params: { id: event.uuid }
      }).then(() => {
        this.filterEvents(this.lastFilterParams)
      })
    },
    changeStatusMultiple (statusId, events) {
      events.forEach((event) => {
        this.changeStatusSingle(statusId, event)
      })
    },
    async onSubmitDelete () {
      this.deletablesProcessing = true
      this.deletablesProgress = 0
      this.deletablesStatus = ''
      let dispatcheables = []

      this.deleteTemp.forEach((event) => {
        dispatcheables.push(this.$store.dispatch('events/deleteItem', {
          getItems: false,
          params: { id: event.uuid }
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

      this.filterEvents(this.lastFilterParams)
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
      this.$store.dispatch('events/getItems', { params: { include: INCLUDE } })
    },
    filterEvents (params) {
      this.lastFilterParams = params
      this.$store.dispatch('events/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('events/getItems', { params: { include: INCLUDE } })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query,
      ...this.lastFilterParams
    })
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('eventStatuses/getItems'),
      vm.$store.dispatch('eventTypes/getItems')
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
