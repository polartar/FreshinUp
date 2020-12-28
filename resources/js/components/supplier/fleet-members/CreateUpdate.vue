<script>
  import CreateUpdate, { SQUARE_APP_ID, SQUARE_ENVIRONMENT } from '~/components/fleet-members/CreateUpdate.vue'
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
      }),
    },
    beforeRouteEnterOrUpdate (vm, from, to, next) {
      const id = to.params.id || 'new'
      const promises = []
      if (id !== 'new') {
        promises.push(vm.$store.dispatch('eventStatuses/getItems'))
        promises.push(vm.$store.dispatch('suppliers/events/getItems', {
          params: {
            supplierId: vm.currentUser.uuid
          }
        }))
        promises.push(vm.$store.dispatch('suppliers/documents/getItems', {
          params: {
            supplierId: vm.currentUser.uuid
          }
        }))
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
        const currentUser = vm.$store.getters['currentUser']
        if (currentUser) {
          vm.getSquareLocations(currentUser.company_id)
        }
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
      onEventPaginate (value) {
        this.$store.dispatch('supplier/events/setPagination', value)
        this.$store.dispatch('supplier/events/getItems', {
          params: { supplierId: this.$route.params.id }
        })
      },
    }
  }
</script>
