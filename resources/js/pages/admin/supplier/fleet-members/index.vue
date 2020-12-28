<script>
  import Page, { INCLUDE } from '~/pages/admin/fleet-members/index.vue'
  import { mapGetters } from 'vuex'
  export default {
    extends: Page,
    computed: {
      ...mapGetters(['currentUser']),
      ...mapGetters('suppliers/stores', {
        stores: 'items',
        pagination: 'pagination',
        sorting: 'sorting',
        sortBy: 'sortBy',
        storesLoading: 'itemsLoading'
      }),
      newLink () {
        return '/admin/supplier/fleet-members/new'
      },
    },
    async beforeRouteEnterOrUpdate (vm, to, from, next) {
      vm.setPageLoading(true)
      await vm.$store.dispatch('currentUser/getCurrentUser')
      Promise.all([
        vm.$store.dispatch('suppliers/stores/setFilters', {
          ...vm.$route.query,
          ...this.lastFilterParams
        }),
        vm.$store.dispatch('suppliers/stores/getItems', {
          params: {
            supplierId: vm.currentUser.uuid
          }
        }),
        vm.$store.dispatch('storeStatuses/getItems')
      ])
        .then()
        .catch((error) => console.error(error))
        .then(() => {
          vm.setPageLoading(false)
          if (next) next()
        })
    },
    methods: {
      onPaginate (value) {
        this.$store.dispatch('suppliers/stores/setPagination', value)
        this.$store.dispatch('suppliers/stores/getItems', {
          params: {
            supplierId: this.currentUser.uuid
          }
        })
      },
      storeViewOrEdit (store) {
        this.$router.push({ path: `/admin/supplier/fleet-members/${store.uuid}/edit` })
      },
      filterStores (params) {
        this.lastFilterParams = params
        this.$store.dispatch('suppliers/stores/setFilters', {
          ...this.$route.query,
          ...this.lastFilterParams
        })
        this.$store.dispatch('suppliers/stores/getItems', {
          params: {
            supplierId: this.currentUser.uuid,
            include: INCLUDE
          }
        })
      }
    }
  }
</script>
