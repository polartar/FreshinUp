<template>
  <v-card>
    <v-card-title class="px-3">
      <v-layout
        align-center
        justify-center
        row
        fill-height
      >
        <v-flex>
          <h3 class="grey--text">
            Assigned Events
          </h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            v-model="newLocationDialog"
            max-width="400"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                dark
                @click="newLocationDialog = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add New Location
              </v-btn>
            </template>
            <v-card>
              <v-divider />
              <v-card-text class="grey--text">
                Coming Soon
              </v-card-text>
              <v-divider />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <hr>

    <v-card-text class="ma-2">
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        :sortables="statuses"
        without-expansion
        icon="search"
        color="transparent"
        class="filter-transparent"
        @runFilter="$emit('runFilter', $event)"
      />

      <f-data-table
        :headers="headers"
        :items="items"
        :is-loading="isLoading"
        :item-actions="itemActions"
        :multi-item-actions="itemActions"
        item-key="id"
        v-bind="$attrs"
        v-on="$listeners"
      >
        <template v-slot:item-inner-status_id="{ item }">
          <status-select
            v-model="item.status_id"
            :options="statuses"
            @input="changeStatus($event, item)"
          />
        </template>
        <template v-slot:item-inner-name,tags="{ item }">
          <div class="grey--text">
            {{ item.name }}
          </div>
          <div v-if="item.tags.length">
            <v-chip
              v-for="(tag, index) of item.tags"
              :key="index"
              color="orange"
            >
              {{ tag.name }}
            </v-chip>
          </div>
        </template>
        <template v-slot:item-inner-start_at,venue="{ item }">
          <div class="grey--text">
            {{ item.start_at }}
          </div>
          <div class="grey--text">
            @{{ item.venue && item.venue.name }}
          </div>
        </template>
        <template v-slot:item-inner-manager="{ item }">
          <div class="grey--text">
            {{ item.manager && item.manager.name }}
          </div>
        </template>
        <template v-slot:item-inner-host="{ item }">
          <div class="grey--text">
            {{ item.host && item.host.name }}
          </div>
        </template>
      </f-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
import StatusSelect from '~/components/docs/StatusSelect'
import FilterSorter from '~/components/venues/FilterSorter.vue'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'

export default {
  components: { StatusSelect, FilterSorter, FDataTable },
  props: {
    items: {
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
        { text: 'Title / Category', value: 'name,tags', align: 'left' },
        { text: 'Date/ Venue', sortable: true, value: 'start_at,venue', align: 'left' },
        { text: 'Managed By', value: 'manager', align: 'left' },
        { text: 'Customer', value: 'host', align: 'left' },
        { text: 'Manage', sortable: false, value: 'manage', align: 'left' }
      ],
      itemActions: [
        { action: 'view', text: 'View / Edit' },
        { action: 'delete', text: 'Delete' }
      ],
      actionBtnTitle: 'Manage',
      newLocationDialog: false
    }
  },
  computed: {
    selectedDocActions () {
      if (!this.selected.length) return []
      let actions = []
      actions.push({ action: 'delete', text: 'Delete' })
      return actions
    }
  },
  methods: {
    changeStatus (value, doc) {
      this.$emit('change-status', value, doc)
    },
    changeStatusMultiple (value) {
      this.$emit('change-status-multiple', value, this.selected)
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

