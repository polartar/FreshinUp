<script>
import companies from '~/pages/admin/companies/index.vue'
import { mapGetters } from 'vuex'

export default {
  extends: companies,
  computed: {
    ...mapGetters(['currentUser'])
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('companies/setFilters', { ...vm.$route.query, uuid: vm.currentUser.company.uuid })
    Promise.all([
      vm.$store.dispatch('companies/getItems', { params: { include: 'users' } })
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
