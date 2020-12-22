<script>
import CompaniesPage from 'fresh-bus/pages/admin/companies/index.vue'
import companiesList from '~/components/datatable/CompaniesList.vue'
import companiesFilter from '~/components/companies/FilterSorter.vue'

export default {
  components: {
    companiesList,
    companiesFilter
  },
  extends: CompaniesPage,

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('companies/setFilters', { ...vm.$route.query, users_id: vm.$store.getters.currentUser.id })
    Promise.all([
      vm.$store.dispatch('companies/getItems', { params: { include: 'users' } })
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
