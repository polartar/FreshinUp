<script>
import CreateUpdate from '~/components/docs/CreateUpdate.vue'

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
