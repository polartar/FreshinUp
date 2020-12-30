<template>
  <v-container v-if="!isLoading">
    <v-flex
      d-flex
      align-center
      justify-space-between
      ma-3
    >
      <v-layout
        flex
        align-center
      >
        <h2 class="white--text mr-3">
          Fleet Member: {{ store.name }}
        </h2>
      </v-layout>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <f-static-status
          :statuses="storeStatuses"
          :value="store.status"
        />
      </v-flex>
    </v-flex>
    <v-divider />

    <fleet-member-docs
      :docs="docs"
      :statuses="docsStatuses"
      :sortables="docsSortables"
      :rows-per-page="docsPagination.rowsPerPage"
      :page="docsPagination.page"
      :total-items="docsPagination.totalItems"
      :sort-by="docsSorting.sortBy"
      :descending="docsSorting.descending"
      @paginate="docsOnPaginate"
      @change-status="docsChangeStatus"
      @manage-view="docsView"
      @manage-edit="docsEdit"
      @manage-delete="docsDelete"
      @manage-multiple-delete="docsDelete"
      @change_status_multiple="docsChangeStatusMultiple"
      @runFilter="docsFilter"
    />

    <assigned-events
      :events="events"
      :all-events-count="store.events_count"
      :statuses="eventStatuses"
      :rows-per-page="eventsPagination.rowsPerPage"
      :page="eventsPagination.page"
      :total-items="eventsPagination.totalItems"
      :sort-by="eventsSorting.eventsSortBy"
      :descending="eventsSorting.descending"
      @paginate="onPaginateEvents"
      @viewEvent="viewEvent"
      @runFilter="filterEvents"
    />

    <docs-delete-dialog
      v-model="docsDeleteDialog"
      :delete-temp="docsDeleteTemp"
      :on-submit-delete="docsOnSubmitDelete"
      :on-cancel-delete="docsOnCancelDelete"
    />
  </v-container>
</template>

<script>
import AssignedEvents from '~/components/events/AssignedEvents.vue'
import FleetMemberDocs from '~/components/docs/FleetMemberDocs.vue'
import DocsDeleteDialog from '~/components/docs/DeleteDialog.vue'
import { mapActions, mapGetters } from 'vuex'
import FStaticStatus from 'fresh-bus/components/ui/FStaticStatus'
import DocsDatatableManager from '~/components/mixins/DocsDatatableManager'

const eventsParams = {
  include: ['status', 'host', 'location.venue', 'manager', 'event_tags']
}

export default {
  layout: 'admin',
  components: {
    AssignedEvents,
    FleetMemberDocs,
    DocsDeleteDialog,
    FStaticStatus
  },
  mixins: [DocsDatatableManager],
  computed: {
    ...mapGetters('stores', { store: 'item' }),
    ...mapGetters('storeStatuses', { storeStatuses: 'items' }),
    ...mapGetters('eventStatuses', { eventStatuses: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', {
      events: 'items',
      eventsPagination: 'pagination',
      eventsSorting: 'sorting',
      eventsSortBy: 'sortBy',
      eventsSortables: 'sortables'
    })
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    onPaginateEvents (value) {
      this.$store.dispatch('events/setPagination', value)
      this.$store.dispatch('events/getItems', {
        params: {
          ...eventsParams,
          'filter[store_uuid]': this.$route.params.id
        }
      })
    },
    viewEvent (event) {
      this.$router.push({ path: `/admin/events/${event.uuid}` })
    },
    filterEvents (params) {
      this.$store.dispatch('events/setFilters', params)
      this.$store.dispatch('events/getItems', {
        params: {
          ...eventsParams,
          'filter[store_uuid]': this.$route.params.id
        }
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query
    })
    const id = to.params.id
    Promise.all([
      vm.$store.dispatch('stores/getItem', {
        params: { id, provide: 'events-count' }
      }),
      vm.$store.dispatch('documents/getItems', {
        params: { 'filter[assigned_uuid]': id }
      }),
      vm.$store.dispatch('eventStatuses/getItems'),
      vm.$store.dispatch('storeStatuses/getItems'),
      vm.$store.dispatch('documentStatuses/getItems')
    ])
      .then(() => {
        if (next) next()
      })
      .catch(error => console.error(error))
      .then(() => vm.$store.dispatch('page/setLoading', false))
  }
}
</script>
