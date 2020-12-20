<template>
  <v-container>
    <v-progress-linear
      v-if="isLoading"
      indeterminate
      height="10"
    />
    <v-layout
      wrap
      row
    >
      <v-flex
        xs8
        class="text-xs-left"
      >
        <v-layout
          wrap
          row
        >
          <v-btn
            large
            color="grey"
            class="ml-0 mt-0 white--text"
            @click="moveToToday"
          >
            Today
          </v-btn>
          <v-flex
            xs4
            ml-2
            class="text-xs-left"
          >
            <v-select
              v-model="currentMonth"
              :items="months"
              label="Month"
              solo
              hide-details
            />
          </v-flex>
          <v-flex
            xs2
            ml-3
            class="text-xs-left"
          >
            <v-select
              v-model="currentYear"
              :items="years"
              label="Year"
              solo
              hide-details
            />
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex
        xs3
        offset-xs1
        class="text-xs-right"
      >
        <v-select
          v-model="type"
          :items="typeOptions"
          label="Type"
          solo
          hide-details
        />
      </v-flex>
      <v-flex
        xs12
        mb-3
        mt-3
      >
        <v-sheet>
          <v-calendar
            ref="calendar"
            v-model="currentDate"
            :type="type"
            color="primary"
            :weekday="[1, 2, 3, 4, 5, 6, 0]"
          >
            <template v-slot:day="{ date }">
              <template v-for="event in eventsMap[date]">
                <div
                  :key="event.name"
                  full-width
                  offset-x
                  class="white--text clickable"
                  :class="colorsByStatusId[event.status_id]"
                  @click="clickEvent(event)"
                  v-text="event.name"
                />
              </template>
            </template>
          </v-calendar>
        </v-sheet>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>

import isEmpty from 'lodash/isEmpty'
import get from 'lodash/get'
import range from 'lodash/range'
import moment from 'moment'

const DATE_FORMAT = 'YYYY-MM-DD'

export const lastThreeYears = () => {
  const today = new Date()
  const year = today.getFullYear()
  return [year - 3, year + 1]
}
export default {
  props: {
    isLoading: { type: Boolean, default: false },
    events: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] },
    type: { type: String, default: 'month' },
    yearRange: { type: Array, default: lastThreeYears },
    date: { type: String, default: '2019-1-1' }
  },
  data () {
    const currentDate = moment(this.date)
    return {
      typeOptions: [
        { text: 'Day', value: 'day' },
        { text: '4 Day', value: '4day' },
        { text: 'Week', value: 'week' },
        { text: 'Month', value: 'month' }
      ],
      years: range.apply(this, this.yearRange),
      months: [
        { text: 'January', value: 1 },
        { text: 'February', value: 2 },
        { text: 'March', value: 3 },
        { text: 'April', value: 4 },
        { text: 'May', value: 5 },
        { text: 'June', value: 6 },
        { text: 'July', value: 7 },
        { text: 'August', value: 8 },
        { text: 'September', value: 9 },
        { text: 'October', value: 10 },
        { text: 'November', value: 11 },
        { text: 'December', value: 12 }
      ],
      currentMonth: currentDate.month() + 1,
      currentYear: currentDate.year(),
      currentDate: currentDate.format(DATE_FORMAT)
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
  watch: {
    currentYear (yearValue) {
      this.moveDate({
        month: this.currentMonth,
        year: yearValue
      })
    },
    currentMonth (monthValue) {
      this.moveDate({
        month: monthValue,
        year: this.currentYear
      })
    }
  },
  methods: {
    moveToToday () {
      const calendarToday = this.$refs.calendar.times.today
      this.currentYear = calendarToday.year
      this.currentMonth = calendarToday.month
    },
    moveDate (date) {
      const currentDate = moment(this.currentDate)
      let years = 0
      let months = 0
      if (date.year) {
        years = date.year - currentDate.year()
      }
      if (date.month) {
        months = date.month - (currentDate.month() + 1)
      }
      this.$emit('change-date', date)
      this.$refs.calendar.move(years * 12 + months)
    },
    clickEvent (evt) {
      this.$emit('click-event', evt)
    }
  }
}

</script>

<style scoped>
  .v-calendar-weekly__day {
    overflow: visible;
  }
  .clickable {
    cursor: pointer;
  }
</style>
