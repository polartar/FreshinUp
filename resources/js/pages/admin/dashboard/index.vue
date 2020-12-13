<template>
  <div class="pa-4">
    <h1 class="white--text">Welcome back, {{ get(currentUser, 'name') }}</h1>

    <v-layout>
      <v-flex xs12 sm9 md8 :class="{'mr-4': $vuetify.breakpoint.smAndUp}">
        <upcoming-events-table/>
      </v-flex>
      <v-flex xs12 sm3 md4>
        <upcoming-events-calendar/>
      </v-flex>
    </v-layout>

    <supplier-fleets
      :is-loading="storesLoading"
      :stores="stores"
      :store-statuses="storeStatuses"
      :status-stats="storeStatusStats"
      @view-all="viewFleets"
    />
  </div>
</template>
<script>

import { mapGetters } from 'vuex'
import UpcomingEventsCalendar from '~/components/supplier/UpcomingEventsCalendar.vue'
import UpcomingEventsTable from '~/components/supplier/UpcomingEventsTable.vue'
import SupplierFleets from '~/components/supplier/SupplierFleets.vue'
import get from 'lodash/get'

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
      eventStatusStats: [], // TODO
      storeStatusStats: [], // TODO
    }
  },
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('eventStatus', {
      eventStatuses: 'items',
    }),
    ...mapGetters('storeStatuses', {
      storeStatuses: 'items',
    }),
    ...mapGetters('events', {
      events: 'items',
      eventsLoading: 'itemsLoading'
    }),
    ...mapGetters('stores', {
      stores: 'items',
      storesLoading: 'itemsLoading'
    }),
  },
  methods: {
    get,
    viewAll () {},
    viewEvents () {},
    viewFleets () {},
    changeEventStatus (value, item) {
      this.$store.dispatch('events/patchItem', {
        data: {
          status_id: value,
        },
        params: {
          id: item.uuid
        }
      })
    },
  },
  async beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    const authUser = await vm.$store.dispatch('currentUser/getCurrentUser')
    const promises = []
    // TODO check/guard that authUser.type = UserType.SUPPLIER

    // events
    vm.$store.dispatch('events/setFilters', {
      host_uuid: authUser.company.uuid,
      include: 'location,venue'
    })
    promises.push(vm.$store.dispatch('eventStatuses/getItems'))
    promises.push(vm.$store.dispatch('events/getItems'))

    // stores (=fleets)
    vm.$store.dispatch('stores/setFilters', {
      supplier_uuid: authUser.company.uuid,
      include: 'type'
    })
    promises.push(vm.$store.dispatch('stores/getItems'))
    promises.push(vm.$store.dispatch('storeStatuses/getItems'))

    //
    Promise.all(promises)
      .then()
      .catch(error => console.error(error))
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
      })
  },

}
</script>
