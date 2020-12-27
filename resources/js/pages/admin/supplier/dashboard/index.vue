<template>
  <div class="pa-4">
    <h1 class="white--text">
      Welcome back, {{ get(currentUser, 'name') }}
    </h1>

    <v-layout
      class="my-4"
      row
      wrap
    >
      <v-flex
        xs12
        sm12
        md9
      >
        <upcoming-events-table
          :class="{'mr-4': $vuetify.breakpoint.mdAndUp}"
          :is-loading="eventsLoading"
          :events="events"
          :statuses="eventStatuses"
          :rows-per-page="eventPagination.rowsPerPage"
          :page="eventPagination.page"
          :total-items="eventPagination.totalItems"
          :sort-by="eventSorting.sortBy"
          :descending="eventSorting.descending"
          @paginate="onEventPaginate"
          @change-status="changeEventStatus"
          @manage-view="viewEvent"
          @manage-delete="deleteEvent"
          @manage-multiple-view="viewEvents"
          @manage-multiple-status="changeEventsStatus"
          @manage-multiple-delete="deleteEvents"
        />
      </v-flex>
      <v-flex
        xs12
        sm12
        md3
      >
        <upcoming-events-calendar
          :class="{'mt-4': $vuetify.breakpoint.smOnly}"
          :events="events"
          :statuses="eventStatuses"
        />
      </v-flex>
    </v-layout>
    <supplier-fleets
      :is-loading="storesLoading"
      :stores="stores"
      :statuses="storeStatuses"
      :status-stats="storeStatusStats"
      :rows-per-page="storePagination.rowsPerPage"
      :page="storePagination.page"
      :total-items="storePagination.totalItems"
      :sort-by="storeSorting.sortBy"
      :descending="storeSorting.descending"
      @paginate="onStorePaginate"
      @change-status="changeStoreStatus"
      @manage-view="viewFleet"
      @manage-delete="deleteFleet"
      @manage-multiple-view="viewFleets"
      @manage-multiple-status="changeStoresStatus"
      @manage-multiple-delete="deleteFleets"
    />
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import UpcomingEventsCalendar from '~/components/supplier/UpcomingEventsCalendar.vue'
import UpcomingEventsTable from '~/components/supplier/UpcomingEventsTable.vue'
import SupplierFleets from '~/components/supplier/SupplierFleets.vue'
import get from 'lodash/get'
import moment from 'moment'
export default {
  layout: 'admin',
  name: 'Dashboard',
  components: {
    SupplierFleets,
    UpcomingEventsCalendar,
    UpcomingEventsTable
  },
  data () {
    return {
    }
  },
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('eventStatuses', {
      eventStatuses: 'items'
    }),
    ...mapGetters('storeStatuses', {
      storeStatuses: 'items'
    }),
    ...mapGetters('suppliers/stores', {
      stores: 'items',
      storesLoading: 'itemsLoading',
      storePagination: 'pagination',
      storeSorting: 'sorting'
    }),
    ...mapGetters('suppliers/stores/stats', {
      storeStatusStats: 'items'
    }),
    ...mapGetters('suppliers/events', {
      events: 'items',
      eventsLoading: 'itemsLoading',
      eventPagination: 'pagination',
      eventSorting: 'sorting'
    })
  },
  methods: {
    get,
    // store
    viewFleet (store) {
      this.$router.push({ path: `/admin/fleet-members/${store.uuid}` })
    },
    viewFleets () {
      this.$router.push({ path: `/admin/fleet-members` })
    },
    deleteFleet (store) {
      this.$store.dispatch('stores/deleteItem', {
        params: {
          id: store.uuid
        }
      })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    },
    deleteFleets (stores) {
      // TODO: bulk delete for stores https://github.com/FreshinUp/foodfleet/issues/645
      Promise.all(stores.map(this.deleteFleet))
        .then(() => {
          this.$store.dispatch('suppliers/stores/getItems')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    },
    changeStoreStatus (value, store) {
      this.$store.dispatch('stores/patchItem', {
        data: {
          status_id: value
        },
        params: {
          id: store.uuid
        }
      })
    },
    changeStoresStatus (stores, status) {
      // TODO: bulk update on store https://github.com/FreshinUp/foodfleet/issues/647
      Promise.all(stores.map(store => this.changeStoreStatus(status.id, store)))
        .then(() => {
          this.$store.dispatch('suppliers/stores/getItems')
        })
        .catch(error => console.error(error))
    },
    onStorePaginate (value) {
      this.$store.dispatch('suppliers/stores/setPagination', value)
      this.$store.dispatch('suppliers/stores/getItems', {
        params: {
          supplierId: this.currentUser.uuid
        }
      })
    },
    // event
    onEventPaginate (value) {
      this.$store.dispatch('suppliers/events/setPagination', value)
      this.$store.dispatch('suppliers/events/getItems', {
        params: {
          supplierId: this.currentUser.uuid
        }
      })
    },
    viewEvent (event) {
      this.$router.push({ path: `/admin/events/${event.uuid}/edit` })
    },
    viewEvents () {
      this.$router.push({ path: `/admin/suppliers/events` })
    },
    deleteEvent (event) {
      this.$store.dispatch('events/deleteItem', {
        params: {
          id: event.uuid
        }
      })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    },
    deleteEvents (events) {
      // TODO: bulk delete for events https://github.com/FreshinUp/foodfleet/issues/645
      Promise.all(events.map(this.deleteEvent))
        .then(() => {
          this.$store.dispatch('suppliers/events/getItems')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    },
    changeEventStatus (value, item) {
      this.$store.dispatch('events/patchItem', {
        data: {
          status_id: value
        },
        params: {
          id: item.uuid
        }
      })
    },
    changeEventsStatus (events, status) {
      // TODO: bulk update on events https://github.com/FreshinUp/foodfleet/issues/646
      Promise.all(events.map(event => this.changeEventStatus(status.id, event)))
        .then(() => {
          this.$store.dispatch('suppliers/events/getItems')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
    }
  },
  async beforeRouteEnterOrUpdate (vm, to, from, next) {
    await vm.$store.dispatch('currentUser/getCurrentUser')
    if (!get(vm.currentUser, 'company.uuid')) {
      console.error('Auth user does not have company uuid')
      return false
    }
    vm.$store.dispatch('page/setLoading', false)
    const promises = []
    // TODO check/guard that vm.currentUser.type = UserType.SUPPLIER
    // events
    const today = moment().format('YYYY-MM-DD')
    promises.push(vm.$store.dispatch('eventStatuses/getItems'))
    promises.push(vm.$store.dispatch('storeStatuses/getItems'))
    vm.$store.dispatch('suppliers/events/setPagination', {
      rowsPerPage: 5
    })
    vm.$store.dispatch('suppliers/events/setFilters', {
      include: 'location,venue',
      start_at: today
    })
    vm.$store.dispatch('suppliers/stores/setPagination', {
      rowsPerPage: 5
    })
    vm.$store.dispatch('suppliers/stores/setFilters', {
      include: 'type'
    })
    promises.push(vm.$store.dispatch('suppliers/events/getItems', {
      params: {
        supplierId: vm.currentUser.uuid
      }
    }))
    promises.push(vm.$store.dispatch('suppliers/stores/getItems', {
      params: {
        supplierId: vm.currentUser.uuid
      }
    }))
    promises.push(vm.$store.dispatch('suppliers/stores/stats/getItems', {
      params: {
        supplierId: vm.currentUser.uuid
      }
    }))
    //
    Promise.all(promises)
      .then()
      .catch(error => console.error(error))
  }
}
</script>
