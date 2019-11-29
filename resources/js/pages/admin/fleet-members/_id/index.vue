<template>
  <v-container>
    <assigned-events
      :events="events"
      :statuses="statusesWithColors"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @paginate="onPaginate"
      @viewEvent="viewEvent"
    />
  </v-container>
</template>

<script>
import AssignedEvents from '~/components/events/AssignedEvents.vue'
import { mapActions, mapGetters, mapState } from 'vuex'
const eventsInclude = [
  'status',
  'host',
  'location.venue',
  'manager',
  'event_tags'
]
export default {
  layout: 'admin',
  components: {
    AssignedEvents
  },
  data () {
    return {
      pagination: {
        page: 1,
        rowsPerPage: 10,
        totalItems: 5
      },
      sorting: {
        descending: false,
        sortBy: ''
      }
    }
  },
  computed: {
    ...mapGetters('events', { events: 'items' }),
    ...mapGetters('eventStatuses', { statuses: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('events', ['sortables']),
    statusesWithColors () {
      let statusColorMap = {
        1: 'accent',
        2: 'warning',
        3: 'success',
        4: 'secondary',
        5: 'accent'
      }
      let statuses = JSON.parse(JSON.stringify(this.statuses))
      if (!statuses.length) return statuses
      for (let i in statuses) {
        statuses[i].color = statusColorMap[statuses[i].id]
      }
      return statuses
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onPaginate (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems', {
        params: { include: eventsInclude }
      })
    },
    viewEvent (event) {
      // TODO: Replace this with the proper route for an event item
      window.location = '/admin/events/' + event.uuid
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query
    })
    Promise.all([
      vm.$store.dispatch('events/getItems', {
        params: { include: eventsInclude }
      }),
      vm.$store.dispatch('eventStatuses/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
