<script>
import get from 'lodash/get'
import CreateUpdate, { SQUARE_APP_ID, SQUARE_ENVIRONMENT, PAYMENT_INCLUDES } from '~/components/fleet-members/CreateUpdate.vue'
import { createHelpers } from 'vuex-map-fields'
import { mapGetters } from 'vuex'
const { mapFields } = createHelpers({
  getterType: 'getField',
  mutationType: 'updateField'
})
export default {
  extends: CreateUpdate,
  layout: CreateUpdate.layout,
  computed: {
    ...mapFields('suppliers/stores', [
      'status_id'
    ]),
    ...mapGetters('suppliers/documents', {
      docs: 'items',
      documentPagination: 'pagination',
      documentSorting: 'sorting'
    }),
    ...mapGetters('suppliers/events', {
      events: 'items',
      eventPagination: 'pagination',
      eventSorting: 'sorting'
    })
  },
  watch: {
    '$store.getters.currentUser' (user) {
      if (user) {
        const promises = []
        promises.push(this.getSupplierEvents())
        promises.push(this.$store.dispatch('suppliers/documents/getItems', {
          params: {
            supplierId: user.uuid
          }
        }))
        promises.push(this.getSquareLocations(user.company_id))
        Promise.all(promises)
          .then()
          .catch(error => {
            console.error(error)
          })
      }
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []
    if (id !== 'new') {
      promises.push(vm.$store.dispatch('eventStatuses/getItems'))
      vm.fleetMemberLoading = true
      vm.$store.dispatch('stores/getItem', {
        params: {
          id,
          include: 'tags,owner'
        }
      })
        .then()
        .catch(error => {
          console.error(error)
          vm.backToList()
        })
        .then(() => {
          vm.fleetMemberLoading = false
        })
      vm.$store.dispatch('storeAreas/setFilters', {
        store_uuid: id
      })
      promises.push(vm.$store.dispatch('storeAreas/getItems'))

      vm.$store.dispatch('menuItems/setFilters', {
        store_uuid: id
      })
      promises.push(vm.$store.dispatch('menuItems/getItems'))
      vm.$store.dispatch('payments/setFilters', {
        store_uuid: id,
        include: PAYMENT_INCLUDES
      })
      promises.push(vm.$store.dispatch('payments/getItems'))
    }
    promises.push(vm.$store.dispatch('documentStatuses/getItems'))
    promises.push(vm.$store.dispatch('documentTypes/getItems'))
    promises.push(vm.$store.dispatch('storeTypes/getItems'))
    promises.push(vm.$store.dispatch('storeStatuses/getItems'))
    promises.push(vm.$store.dispatch('paymentStatuses/getItems'))

    if (!SQUARE_APP_ID) {
      vm.$store.dispatch('generalErrorMessages/setErrors', 'Unable to find square application id')
      return false
    }
    if (!SQUARE_ENVIRONMENT) {
      vm.$store.dispatch('generalErrorMessages/setErrors', 'Unable to find square environment')
      return false
    }

    vm.$store.dispatch('page/setLoading', false)
    Promise.all(promises)
      .then(() => {})
      .catch((error) => {
        console.error(error)
      })
      .then(() => {
        if (next) next()
      })
  },
  methods: {
    onViewDocument (document) {
      this.$router.push({ path: `/admin/supplier/documents/${document.uuid}` })
    },
    backToList () {
      this.$router.push({ path: '/admin/supplier/fleet-members' })
    },
    editEvent (event) {
      this.$router.push({ path: `/admin/supplier/events/${event.uuid}/edit` })
    },
    getSupplierEvents () {
      const userUuid = get(this.currentUser, 'uuid')
      if (!userUuid) {
        return Promise.reject(new Error('[getSupplierEvents] No auth user. Aborting...'))
      }
      return this.$store.dispatch('suppliers/events/getItems', {
        params: { supplierId: userUuid }
      })
    },
    onDuplicateSuccess () {
      this.getSupplierEvents()
    },
    onEventPaginate (value) {
      this.$store.dispatch('suppliers/events/setPagination', value)
      this.getSupplierEvents()
    }
  }
}
</script>
