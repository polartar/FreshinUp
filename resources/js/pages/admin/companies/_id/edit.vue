<script>
import CreateUpdate from '~/components/companies/CreateUpdate.vue'

export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate,
  data () {
    return {
      isNew: false
    }
  }
}
</script>
