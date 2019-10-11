<script>
import CompaniesPage from 'fresh-bus/pages/admin/companies/index.vue'
import CompaniesList from '~/components/datatable/CompaniesList.vue'
import CompaniesFilter from '~/components/companies/FilterSorter.vue'

export default {
  extends: CompaniesPage,
  components: {
    CompaniesList,
    CompaniesFilter
  }
}
</script>