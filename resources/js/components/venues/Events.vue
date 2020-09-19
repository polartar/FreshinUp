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
            v-model="newEventDialog"
            max-width="400"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                dark
                @click="newEventDialog = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add New Event
              </v-btn>
            </template>
            <v-card>
              <event-form />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />

    <v-card-text class="ma-2">
      <filter-sorter
        v-if="!isLoading"
        :statuses="statuses"
        without-expansion
        icon="search"
        color="transparent"
        class="filter-transparent"
        @runFilter="$emit('runFilter', $event)"
      />
      <event-list
        :is-loading="isLoading"
        :items="items"
        :statuses="statuses"
        v-bind="$attrs"
        v-on="$listeners"
      />
    </v-card-text>
  </v-card>
</template>

<script>
import FilterSorter from '~/components/venues/FilterSorter.vue'
import EventList from '../events/EventList'
import EventForm from './EventForm'

export default {
  components: { FilterSorter, EventList, EventForm },
  props: {
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      newEventDialog: false
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
