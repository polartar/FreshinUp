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
          <v-btn
            slot="activator"
            color="primary"
            dark
            href="/admin/events/new"
          >
            <v-icon
              dark
              left
            >
              add_circle_outline
            </v-icon>Add New Event
          </v-btn>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />

    <v-card-text class="ma-2">
      <event-filter-sorter
        without-sort-by
        @runFilter="onFilter"
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
import EventFilterSorter from './EventFilterSorter.vue'
import EventList from './EventList'

export default {
  components: { EventFilterSorter, EventList },
  props: {
    items: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    isLoading: { type: Boolean, default: false }
  },
  methods: {
    onFilter (payload) {
      this.$emit('runFilter', payload)
    }
  }
}
</script>
