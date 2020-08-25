<template>
  <v-timeline
    align-top
    dense
  >
    <v-timeline-item
      v-for="history in histories"
      :key="history.id"
      :color="getColorFor(history)"
      medium
      :icon="getIconFor(history)"
    >
      <div class="ff-event_status_timeline__item">
        <div v-if="history.completed">
          <strong>{{ formatDate(history.date, 'MMM. DD') }}</strong>
          <p class="caption mb-2">
            {{ formatDate(history.date, 'hh:mm A') }}
          </p>
        </div>
        <div v-else />
        <div>
          <strong>{{ history.name }} {{ history.completed ? ': Completed': '' }}</strong>
          <div class="caption">
            {{ history.description }}
          </div>
        </div>
      </div>
    </v-timeline-item>
  </v-timeline>
</template>

<script>
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

// TODO: extract this component to core-ui to FTimeline
export default {
  mixins: [FormatDate],
  props: {
    histories: { type: Array, default: () => [] },
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
  .v-timeline::before {
    height: calc(100% - 36px);
    top: 12px;
  }
  .ff-event_status_timeline__item {
    display: grid;
    grid-template-columns: 1fr;
    position: relative;
  }

  @media only screen and (min-width: 640px) {
    .ff-event_status_timeline__item {
      grid-template-columns: 1fr 5fr;
    }
  }
</style>
