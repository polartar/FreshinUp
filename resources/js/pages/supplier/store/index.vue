
<script>
import stores from '~/pages/admin/fleet-members/index.vue'
import { mapGetters } from 'vuex'
const INCLUDE = [
  'tags',
  'addresses',
  'status',
  'owner',
  'type'
]

export default {
  extends: stores,
  computed: {
    ...mapGetters(['currentUser'])
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    Promise.all([
      vm.$store.dispatch('stores/setFilters', {
        ...vm.$route.query,
        ...this.lastFilterParams,
        owner_uuid: vm.currentUser.uuid
      }),
      vm.$store.dispatch('storeStatuses/getItems', {
        params: { include: INCLUDE }
      })
    ])
      .then(() => {
        if (next) next()
      })
      .catch((error) => console.error(error))
      .then(() => vm.setPageLoading(false))
  }
}
</script>
