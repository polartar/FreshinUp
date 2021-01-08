<script>
import Page from '~/pages/admin/docs/index.vue'
import { mapGetters } from 'vuex'
export default {
  extends: Page,
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('suppliers/documents', {
      docs: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy',
      sortables: 'sortables'
    })
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('suppliers/documents/setFilters', {
      ...vm.$route.query
    })
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('suppliers/documents/getItems', {
        params: {
          supplierId: vm.currentUser.uuid
        }
      }),
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
