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
      <event-filter-sorter
        without-sort-by
        color="transparent"
        class="filter-transparent"
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
import EventForm from '../events/EventForm'

export default {
  components: { EventFilterSorter, EventList, EventForm },
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
  methods: {
    onFilter (payload) {
      this.$emit('runFilter', payload)
    }
  }
}
</script>
