<template>
  <v-card>
    <v-card-title justify-space-between align-center>
      <span class="grey--text">Upcoming events</span>
      <v-spacer/>
      <v-btn color="primary" round @click="viewAll">View All</v-btn>
    </v-card-title>
    <v-divider/>
    <v-card-text>
      <v-layout row justify-space-between align-center>
        <v-btn
          icon
          @click="previous"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <span class="font-weight-bold text-uppercase">{{ formatDate(value, 'MMMM Y') }}</span>
        <v-btn
          icon
          @click="next"
        >
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-layout>
      <v-calendar
        ref="calendar"
        v-model="value"
        :weekdays="weekday"
        type="month"
        color="primary"
      >
        <template v-slot:day="{ date }">
          <template v-for="event in eventsMap[date]">
            <span
              :key="event.name"
              :class="colorsByStatusId[event.status_id]"
              class="ff-upcoming-events__event-dot"
            />
          </template>
        </template>
      </v-calendar>
    </v-card-text>
  </v-card>
</template>

<script>
  import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
  import get from 'lodash/get'
  import isEmpty from 'lodash/isEmpty'
  import moment from 'moment'

  const DATE_FORMAT = 'YYYY-MM-DD'

  export default {
    props: {
      events: { type: Array, default: () => [] },
      statuses: { type: Array, default: () => [] }
    },
    mixins: [FormatDate],
    data () {
      return {
        value: new Date(),
        weekday: [1, 2, 3, 4, 5, 6, 0],
      }
    },
    computed: {
      colorsByStatusId () {
        return this.statuses.reduce((map, status) => {
          map[status.id] = status.color
          return map
        }, {})
      },
      eventsMap () {
        return this.events.reduce((map, evt) => {
          const occurrences = get(evt, 'schedule.schedule_occurrences', null)
          if (isEmpty(occurrences)) {
            const startMoment = moment(evt.start_at, DATE_FORMAT)
            const endMoment = moment(evt.end_at, DATE_FORMAT)
            let startDate = startMoment.format(DATE_FORMAT)
            const endDate = endMoment.format(DATE_FORMAT)
            while (startDate <= endDate) {
              map[startDate] = map[startDate] || []
              map[startDate].push(evt)
              startDate = startMoment.add(1, 'days').format(DATE_FORMAT)
            }
          } else {
            occurrences.forEach(occurrence => {
              const startAt = moment(occurrence.start_at, DATE_FORMAT).format(DATE_FORMAT)
              map[startAt] = map[startAt] || []
              map[startAt].push(evt)
            })
          }
          return map
        }, {})
      }
    },
    methods: {
      viewAll () {
        this.$emit('manage-multiple-view')
        this.$emit('manage-multiple', 'view')
      },
      previous () {
        this.$refs.calendar.prev()
      },
      next () {
        this.$refs.calendar.next()
      },
    }
  }
</script>

<style scoped>
  .v-calendar-weekly__day {
    overflow: visible;
  }
  .ff-upcoming-events__event-dot {
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    display: inline-block;
  }
</style>
