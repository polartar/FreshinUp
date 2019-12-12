<template>
  <v-container v-if="!isLoading">
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
          Fleet Member: {{ store.name }}
        </h2>
      </v-layout>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <f-static-status
          :statuses="storeStatusesWithColors"
          :value="store.status"
        />
      </v-flex>
    </v-flex>
    <v-divider />

    <assigned-events
      :events="events"
      :all-events-count="store.events_count"
      :statuses="eventStatusesWithColors"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @paginate="onPaginate"
      @viewEvent="viewEvent"
      @runFilter="filterEvents"
    />
  </v-container>
</template>

<script>
import AssignedEvents from '~/components/events/AssignedEvents.vue'
import { mapActions, mapGetters, mapState } from 'vuex'
import FStaticStatus from 'fresh-bus/components/ui/FStaticStatus'
const eventsParams = {
  include: ['status', 'host', 'location.venue', 'manager', 'event_tags']
}

const statusesColored = (statuses, statusColorMap) => {
  if (!statuses.length) return statuses
  for (let i in statuses) {
    statuses[i].color = statusColorMap[statuses[i].id]
  }
  return statuses
}

export default {
  layout: 'admin',
  components: {
    AssignedEvents,
    FStaticStatus
  },
  computed: {
    ...mapGetters('stores', { store: 'item' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('eventStatuses', { eventStatuses: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('events', ['sortables']),
    ...mapGetters('events', {
      events: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),

    storeStatusesWithColors () {
      return statusesColored(JSON.parse(JSON.stringify(this.storeStatuses)), {
        1: 'accent',
        2: 'warning',
        3: 'success',
        4: 'danger',
        5: 'success',
        6: 'accent'
      })
    },

    eventStatusesWithColors () {
      return statusesColored(JSON.parse(JSON.stringify(this.eventStatuses)), {
        1: 'accent',
        2: 'warning',
        3: 'success',
        4: 'secondary',
        5: 'accent'
      })
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onPaginate (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems', {
        params: {
          ...eventsParams,
          'filter[store_uuid]': this.$route.params.id
        }
      })
    },
    viewEvent (event) {
      // TODO: Replace this with the proper route for an event item
      window.location = '/admin/events/' + event.uuid
    },
    filterEvents (params) {
      this.$store.dispatch('events/setFilters', params)
      this.$store.dispatch('events/getItems', {
        params: {
          ...eventsParams,
          'filter[store_uuid]': this.$route.params.id
        }
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query
    })
    const id = to.params.id
    Promise.all([
      vm.$store.dispatch('stores/getItem', {
        params: { id, provide: 'events-count' }
      }),
      vm.$store.dispatch('eventStatuses/getItems'),
      vm.$store.dispatch('storeStatuses/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
