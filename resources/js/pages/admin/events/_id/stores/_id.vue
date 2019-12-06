<script>
import StoreDetails from '~/components/events/StoreDetails.vue'

export default {
  extends: StoreDetails,
  layout: StoreDetails.layout,
  beforeRouteEnterOrUpdate: StoreDetails.beforeRouteEnterOrUpdate
}
</script>
