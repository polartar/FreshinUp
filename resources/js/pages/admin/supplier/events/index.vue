<script>
import Page, { INCLUDE } from '~/pages/admin/events/index.vue'
import { mapGetters } from 'vuex'
export default {
  extends: Page,
  computed: {
    ...mapGetters('suppliers/events', {
      events: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy',
      sortables: 'sortables'
    })
  },
  methods: {
    eventNew () {
      this.$router.push({ path: '/admin/supplier/events/new' })
    },
    eventEdit (event) {
      this.$router.push({ path: '/admin/supplier/events/' + event.uuid + '/edit' })
    },
    onPaginate (value) {
      this.$store.dispatch('suppliers/events/setPagination', value)
      this.$store.dispatch('suppliers/events/getItems', {
        params: {
          include: INCLUDE,
          supplierId: this.currentUser.uuid
        }
      })
    },
    filterEvents (params) {
      this.lastFilterParams = params
      this.$store.dispatch('suppliers/events/setFilters', {
        ...this.$route.query,
        ...this.lastFilterParams
      })
      this.$store.dispatch('suppliers/events/getItems', {
        params: {
          include: INCLUDE,
          supplierId: this.currentUser.uuid
        }
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('suppliers/events/setFilters', {
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
