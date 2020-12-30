<script>
import CreateUpdate from '~/components/events/CreateUpdate.vue'
import { mapGetters } from 'vuex'
import { createHelpers } from 'vuex-map-fields'

const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})
export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  beforeRouteEnterOrUpdate: CreateUpdate.beforeRouteEnterOrUpdate,
  computed: {
    ...mapGetters('events', { event: 'item' }),
    ...mapGetters('events/stores', { storeItems: 'items' }),
    ...mapFields('events', [
      'status_id'
    ])
  },
  methods: {
    backToList () {
      this.$router.push({ path: '/admin/supplier/events' })
    },
    editEvent (event) {
      this.$router.push({ path: `/admin/supplier/events/${event.uuid}/edit` })
    }
  }
}
</script>
