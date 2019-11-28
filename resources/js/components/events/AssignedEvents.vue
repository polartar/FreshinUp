<template>
  <v-card>
    <v-card-title class="px-3">
      <h3>Assigned Events</h3>
    </v-card-title>
    <hr>

    <v-card-text
      v-if="!events.length"
      class="text-xs-center ma-5"
    >
      Event data will populate once your restaurant is assigned.
    </v-card-text>
    <v-card-text
      v-else
      class="ma-2"
    >
      <v-layout
        align-center
        justify-center
        row
        fill-height
      >
        <v-flex
          grow
          pa-1
        >
          <v-text-field
            label="Search"
            prepend-inner-icon="search"
            solo
            hide-details
          />
        </v-flex>
        <v-flex
          shrink
          pl-3
        >
          Sort by
        </v-flex>
        <v-flex
          shrink
          pa-1
        >
          <f-btn-menu
            :items="sortables"
            @item="sort"
          >
            Sort
          </f-btn-menu>
        </v-flex>
      </v-layout>

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
              v-if="props.item.event_tags.length"
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
import FBtnMenu from 'fresh-bus/components/ui/FBtnMenu'
import FChip from 'fresh-bus/components/ui/FChip'
import FormatRangeDate from '~/components/mixins/FormatRangeDate'

export default {
  components: { FBtn, FBtnMenu, FChip, FStaticStatus },
  mixins: [Pagination, FormatRangeDate],
  props: {
    events: {
      type: Array,
      default: () => []
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
        { text: 'Status', sortable: false, value: 'status_id', align: 'left' },
        { text: 'Name / Category', value: 'name,event_tags', align: 'left' },
        { text: 'Date / Venue', value: 'start_at,venue', align: 'left' },
        { text: 'Managed By', value: 'manager', align: 'left' },
        { text: 'Customer', value: 'host', align: 'left' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
      ],
      sortables: [{
        id: 'status',
        label: 'Status'
      }, {
        id: 'name',
        label: 'Name'
      }, {
        id: 'date',
        label: 'Date'
      }]
    }
  },
  methods: {
    viewEvent (item) {
      this.$emit('viewEvent', item)
    },
    sort (item) {

    }
  }
}
</script>

<style lang="styl" scoped>
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
