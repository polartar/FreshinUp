<template>
  <div>
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
          {{ pageTitle }}
        </h2>
        <v-btn
          slot="activator"
          color="white"
          @click="eventNew"
        >
          <span class="primary--text">Add New Event</span>
        </v-btn>
      </v-layout>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <v-select
          v-model="view"
          :items="views"
          single-line
          solo
          flat
          hide-details
        />
      </v-flex>
    </v-flex>
    <v-divider />
    <event-list
      v-if="!isLoading"
      :events="events"
      :statuses="statuses"
      :is-loading="isLoading || isLoadingList"
      :rows-per-page="pagination.rowsPerPage"
      :page="pagination.page"
      :total-items="pagination.totalItems"
      :sort-by="sorting.sortBy"
      :descending="sorting.descending"
      @manage-edit="eventEdit"
      @manage-delete="deleteSingle"
      @manage-multiple-delete="multipleDelete"
      @change-status="changeStatusSingle"
      @change-status-multiple="changeStatusMultiple"
    />
    <v-dialog
      v-model="deleteDialog"
      max-width="500"
    >
      <simple-confirm
        :class="{ 'deleting': deletablesProcessing }"
        :title="deleteDialogTitle"
        ok-label="Yes"
        cancel-label="No"
        @ok="onSubmitDelete"
        @cancel="onCancelDelete"
      >
        <div class="py-5 px-2">
          <template v-if="deletablesProcessing">
            <div class="text-xs-center">
              <p class="subheading">
                Processing, please wait...
              </p>
              <v-progress-circular
                :rotate="-90"
                :size="200"
                :width="15"
                :value="deletablesProgress"
                color="primary"
              >
                {{ deletablesStatus }}
              </v-progress-circular>
            </div>
          </template>
          <template v-else>
            <p class="subheading">
              <span v-if="deletables.length < 2">Event</span>
              <span v-else> Events</span>
              : {{ deleteTemp | formatDeleteTitles }}
            </p>
          </template>
        </div>
      </simple-confirm>
    </v-dialog>
  </div>
</template>
<script>
import get from 'lodash/get'
import { mapGetters, mapActions, mapState } from 'vuex'
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import EventList from '~/components/events/EventList.vue'
import SimpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
export default {
  layout: 'admin',
  components: {
    EventList,
    SimpleConfirm
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.title).join(', ')
    }
  },
  mixins: [deletables],
  data () {
    return {
      pageTitle: 'Events',
      deleteDialog: false,
      deleteTemp: [],
      deletablesProcessing: false,
      deletablesProgress: 0,
      deletablesStatus: '',
      view: 1,
      views: [
        { value: 1, text: 'List view' },
        { value: 2, text: 'Calendar view' }
      ]
    }
  },
  computed: {
    isLoadingList () {
      return get(this.$store, 'state.events.pending.items', true)
    },
    ...mapGetters('events', {
      events: 'items',
      pagination: 'pagination',
      sorting: 'sorting',
      sortBy: 'sortBy'
    }),
    ...mapGetters('eventStatuses', { 'statuses': 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapState('events', ['sortables']),
    deleteDialogTitle () {
      return this.deleteTemp.length < 2 ? 'Are you sure you want to delete this event?' : 'Are you sure you want to delete the following events?'
    }
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    eventNew () {
      this.$router.push({ path: '/admin/events/new' })
    },
    eventEdit (event) {
      this.$router.push({ path: '/admin/events/' + event.uuid })
    },
    deleteSingle (event) {
      // to do
    },
    multipleDelete (events) {
      // to do
    },
    changeStatusSingle (status, event) {
      // to do
    },
    changeStatusMultiple (status, events) {
      // to do
    },
    onSubmitDelete () {
      // to do
    },
    onCancelDelete () {
      this.deleteDialog = false
      this.deleteTemp = []
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    vm.$store.dispatch('events/setFilters', {
      ...vm.$route.query
    })
    Promise.all([
      vm.$store.dispatch('eventStatuses/getItems'),
      vm.$store.dispatch('events/getItems')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>
