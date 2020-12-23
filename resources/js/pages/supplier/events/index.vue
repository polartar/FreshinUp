
<script>
import { mapActions, mapGetters } from 'vuex'
import events from '~/pages/admin/events/index.vue'

const INCLUDE = [
  'status',
  'host',
  'location',
  'manager',
  'type',
  'venue'
]

export default {
  extends: events,
  computed: {
    ...mapGetters(['currentUser'])
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onPaginate (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems', { params: { include: INCLUDE, manager_uuid: this.currentUser.uuid } })
    },
    filterEvents (params) {
      this.lastFilterParams = params
      this.$store.dispatch('events/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams,
        manager_uuid: this.currentUser.uuid
      })
      this.$store.dispatch('events/getItems', { params: { include: INCLUDE, manager_uuid: this.currentUser.uuid } })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query,
      ...this.lastFilterParams,
      manager_uuid: vm.currentUser.uuid
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
