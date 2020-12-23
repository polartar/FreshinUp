
<script>
import { mapGetters } from 'vuex'
import Documents from '~/pages/admin/docs/index.vue'

export default {
  extends: Documents,
  computed: {
    ...mapGetters(['currentUser'])
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('documents/setFilters', {
      ...vm.$route.query,
      assigned_uuid: vm.currentUser.uuid
    })
    vm.setPageLoading(true)
    Promise.all([
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
