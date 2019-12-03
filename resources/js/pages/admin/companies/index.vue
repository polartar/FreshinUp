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
  beforeRouteEnterOrUpdate: CompaniesPage.beforeRouteEnterOrUpdate
}
</script>
