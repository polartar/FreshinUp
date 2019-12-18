<template>
  <v-card>
    <v-card-title class="px-3">
      <h3 class="primary--text">
        Assigned Events
      </h3>
    </v-card-title>
    <hr>

    <v-card-text
      v-if="!eventsCount"
      class="text-xs-center pa-5 primary--text"
    >
      Event data will populate once your restaurant is assigned.
    </v-card-text>
    <v-card-text
      v-else
      class="ma-2"
    >
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        without-expansion
        icon="search"
        @runFilter="$emit('runFilter', $event)"
      />

      <v-data-table
        v-model="selected"
        class="event-list-table mt-4"
        :headers="headers"
        :items="events"
        :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
        :pagination.sync="pagination"
        :loading="isLoading"
        :total-items="totalItems"
        item-key="uuid"
        disable-initial-sort
      >
        <v-progress-linear
          slot="progress"
          indeterminate
          height="10"
        />
        <template
          slot="headerCell"
          slot-scope="props"
        >
          <span>{{ props.header.text }}</span>
        </template>
        <template slot="no-data">
          <v-alert
            :value="true"
            color="error"
            icon="warning"
          >
            Sorry, nothing to display here :(
          </v-alert>
        </template>

        <template
          slot="items"
          slot-scope="props"
        >
          <td class="select-td">
            <f-static-status
              :statuses="statuses"
              :value="props.item.status_id"
            />
          </td>
          <td>
            <div class="subheading primary--text">
              {{ props.item.name }}
            </div>
            <div
              v-if="props.item.event_tags && props.item.event_tags.length"
              class="text-no-wrap"
            >
              <f-chip color="secondary">
                {{ props.item.event_tags[0].name }}
              </f-chip>
              <template
                v-if="props.item.event_tags.length > 1"
              >
                + {{ props.item.event_tags.length - 1 }} More
              </template>
            </div>
          </td>
          <td>
            <div class="grey--text">
              {{ formatRangeDate(props.item.start_at, props.item.end_at) }}
            </div>
            <div class="grey--text">
              @{{ props.item.venue && props.item.venue.name }}
            </div>
          </td>
          <td>
            <div class="grey--text">
              {{ props.item.manager && props.item.manager.name }}
            </div>
          </td>
          <td>
            <div class="grey--text">
              {{ props.item.host && props.item.host.name }}
            </div>
          </td>
          <td>
            <f-btn
              color="success"
              @click="viewEvent(props.item)"
            >
              View
            </f-btn>
          </td>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import FStaticStatus from 'fresh-bus/components/ui/FStaticStatus'
import FBtn from 'fresh-bus/components/ui/FBtn'
import FChip from 'fresh-bus/components/ui/FChip'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'
import FilterSorter from '~/components/events/FilterSorter.vue'

export default {
  components: { FBtn, FChip, FStaticStatus, FilterSorter },
  mixins: [Pagination, FormatRangeDate],
  props: {
    events: {
      type: Array,
      default: () => []
    },
    allEventsCount: {
      type: [Number, null],
      default: null
    },
    statuses: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      selected: [],
      headers: [
        { text: 'Status', value: 'status_id', align: 'left' },
        {
          text: 'Name / Category',
          value: 'name,event_tags',
          align: 'left'
        },
        {
          text: 'Date / Venue',
          value: 'start_at',
          align: 'left'
        },
        {
          text: 'Managed By',
          sortable: false,
          value: 'manager',
          align: 'left'
        },
        { text: 'Customer', sortable: false, value: 'host', align: 'left' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
      ],
      sortables: [
        {
          id: 'status',
          label: 'Status'
        },
        {
          id: 'name',
          label: 'Name'
        },
        {
          id: 'date',
          label: 'Date'
        }
      ]
    }
  },
  computed: {
    eventsCount () {
      // If allEventsCount is passed in as a prop, use it, otherwise use the events array length
      if (this.allEventsCount === null) return this.events.length
      return this.allEventsCount
    }
  },
  methods: {
    viewEvent (item) {
      this.$emit('viewEvent', item)
    },
    sort (item) {
      this.$emit('sort', item.id)
    },
    searchInput (val) {
      this.$emit('searchInput', val)
    }
  }
}
</script>

<style scoped>
/deep/ .filter-sorter-container .v-text-field .v-input__slot {
  border: 1px solid #ccc;
  margin-bottom: 0;
}
/deep/ .filter-sorter-container .v-text-field__details {
  display: none;
}
/deep/ .filter-sorter-container .v-divider {
  display: none;
}
/deep/ .filter-sorter-expanded-layout {
  display: none;
}

.highlight {
  background: #ffa;
}
/deep/ table.v-table tbody td.select-td {
  padding-top: 5px;
  padding-bottom: 5px;
  padding-left: 16px;
}
/deep/ table.v-table tbody td.tag-td {
  width: 15%;
}
</style>
