<template>
  <v-timeline
    dense
  >
    <v-timeline-item
      v-for="(option, optionIndex) in options"
      :key="optionIndex"
      :color="getColorFor(option)"
      medium
      :icon="getIconFor(option)"
    >
      <div class="ff-event_status_timeline__item">
        <div v-if="option.completed">
          <strong>{{ formatDate(option.date, 'MMM. DD') }}</strong>
          <p class="caption mb-2">
            {{ formatDate(option.date, 'hh:mm A') }}
          </p>
        </div>
        <div v-else />
        <div>
          <strong>{{ option.name }} {{ option.completed ? ': Completed': '' }}</strong>
          <div class="caption">
            {{ option.description }}
          </div>
        </div>
      </div>
    </v-timeline-item>
  </v-timeline>
</template>

<script>
import FormatDate from 'fresh-bus/components/mixins/FormatDate'

export const EXCEPTION_STATUSES = [8, 9]

// TODO: extract this component to core-ui to FTimeline
export default {
  mixins: [FormatDate],
  props: {
    histories: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    status: { type: Number, default: 0 }
  },
  computed: {
    historiesByStatus () {
      return this.histories.reduce((map, history) => {
        map[history.status_id] = history
        return map
      }, new Map())
    },
    options () {
      return this.statuses.reduce((options, status) => {
        const history = this.historiesByStatus[status.id] || {}
        if (EXCEPTION_STATUSES.includes(status.id) && this.status !== status.id) {
        } else {
          options.push({
            status_id: status.id,
            name: status.name,
            description: history.description,
            date: history.date,
            completed: history.completed
          })
        }
        return options
      }, [])
    }
  },
  methods: {
    getColorFor (option) {
      if (option.status_id === this.status) {
        return 'warning lighten-2'
      } else if (option.completed) {
        return 'success'
      } else {
        return 'grey lighten-2'
      }
    },
    getIconFor (option) {
      return option.completed ? 'check_circle_outline' : ''
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
