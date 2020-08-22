<script>
import CreateUpdate from '~/components/fleet-members/CreateUpdate'

export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate,
  data () {
    return {
      isNew: true
    }
  }
}
</script>
