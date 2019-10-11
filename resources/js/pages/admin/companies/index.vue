<script>
import CompaniesPage from 'fresh-bus/pages/admin/companies/index.vue'
import CompaniesList from '~/components/datatable/CompaniesList.vue'
import CompaniesFilter from '~/components/companies/FilterSorter.vue'

export default {
  components: {
    CompaniesList,
    CompaniesFilter
  },
  extends: CompaniesPage
}
</script>
