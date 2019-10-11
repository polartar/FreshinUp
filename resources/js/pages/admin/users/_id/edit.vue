<script>
import CreateUpdate from 'fresh-bus/components/pages/admin/users/CreateUpdate.vue'

export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate,
  data () {
    return {
      enabledFields: [
        'company',
        'title',
        'mobile_phone',
        'office_phone',
        'notes',
        'level',
        'type',
        'street',
        'street_2',
        'city',
        'country_id',
        'post_code'
      ]
    }
  }
}
</script>
