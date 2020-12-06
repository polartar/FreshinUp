<template>
  <v-timeline
    dense
    align-top
  >
    <v-timeline-item
      v-for="(history, optionIndex) in options"
      :key="optionIndex"
      :color="getColorFor(history)"
      medium
      :icon="getIconFor(history)"
    >
      <div class="ff-event_status_timeline__item">
        <div>
          <strong>{{ formatDate(history.date, 'MMM. DD') }}</strong>
          <p class="caption mb-2">
            {{ formatDate(history.date, 'hh:mm A') }}
          </p>
        </div>
        <div>
          <strong>{{ history.name }}</strong>
          <div class="caption">
            {{ history.description }}
          </div>
        </div>
      </div>
    </v-timeline-item>
  </v-timeline>
</template>

<script>
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'

// TODO: extract this component to core-ui to FTimeline
export default {
  mixins: [FormatDate],
  props: {
    histories: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    status: { type: Number, default: 0 }
  },
  computed: {
    statusesById () {
      return this.statuses.reduce((map, status) => {
        map[status.id] = status
        return map
      }, new Map())
    },
    options () {
      return this.histories.map(history => {
        const status = this.statusesById[history.status_id] || {}
        return {
          ...history,
          name: status.name
        }
      })
    }
  },
  methods: {
    getColorFor (option) {
      if (option.status_id === this.status) {
        return 'warning lighten-2'
      } else {
        return 'success'
      }
    },
    getIconFor (option) {
      return option.status_id !== this.status ? 'check_circle_outline' : ''
    }
  }
}
</script>

<style scoped lang="scss">
  .v-timeline::before {
    height: calc(100% - 4rem);
    top: 2rem;
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
