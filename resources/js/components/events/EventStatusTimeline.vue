<template>
  <v-timeline
    align-top
    dense
  >
    <v-timeline-item
      v-for="event in statuses"
      :key="event.id"
      :color="getColorFor(event)"
      medium
      :icon="getIconFor(event)"
    >
      <div class="ff-event_status_timeline__item">
        <div v-if="event.completed">
          <strong>{{ formatDate(event.date, 'MMM. DD') }}</strong>
          <p class="caption mb-2">
            {{ formatDate(event.date, 'hh:mm A') }}
          </p>
        </div>
        <div v-else />
        <div>
          <strong>{{ event.name }}{{ event.completed ? ': Completed': '' }}</strong>
          <div class="caption">
            {{ event.description }}
          </div>
        </div>
      </div>
    </v-timeline-item>
  </v-timeline>
</template>

<script>
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export default {
  mixins: [FormatDate],
  props: {
    statuses: { type: Array, default: () => [] },
    status: { type: Number, default: 0 }
  },
  methods: {
    getColorFor (event) {
      if (event.completed) {
        return 'success'
      } else if (event.id === this.status) {
        return 'warning lighten-2'
      } else {
        return 'grey lighten-2'
      }
    },
    getIconFor (event) {
      return event.completed ? 'check_circle_outline' : ''
    }
  }
}
</script>

<style scoped lang="scss">
  .ff-event_status_timeline__item {
    display: grid;
    grid-template-columns: 1fr 5fr;
  }
</style>
