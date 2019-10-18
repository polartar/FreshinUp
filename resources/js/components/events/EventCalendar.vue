<template>
  <v-container>
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
            class="ml-0 mt-0"
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
        <v-sheet height="431">
          <v-calendar
            ref="calendar"
            v-model="currentDate"
            :type="type"
            color="primary"
          />
        </v-sheet>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>

import _ from 'lodash'

export default {
  props: {
    events: {
      type: Array,
      default: () => []
    },
    type: {
      type: String,
      default: 'month'
    },
    yearRange: {
      type: Array,
      default: () => [2017, 2020]
    },
    year: {
      type: Number,
      default: 2019
    },
    month: {
      type: Number,
      default: 1
    },
    day: {
      type: Number,
      default: 1
    }
  },
  data: function () {
    return {
      typeOptions: [
        { text: 'Day', value: 'day' },
        { text: '4 Day', value: '4day' },
        { text: 'Week', value: 'week' },
        { text: 'Month', value: 'month' }
      ],
      years: _.range.apply(this, this.yearRange),
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
      currentMonth: this.month,
      currentYear: this.year,
      currentDate: [this.year, this.month, this.day].join('-')
    }
  },
  watch: {
    currentYear: function (yearValue) {
      this.moveDate({
        year: yearValue
      })
    },
    currentMonth: function (monthValue) {
      this.moveDate({
        month: monthValue
      })
    }
  },
  methods: {
    moveToToday: function () {
      const calendarToday = this.$refs.calendar.times.today
      this.currentYear = calendarToday.year
      this.currentMonth = calendarToday.month
    },
    moveDate: function (date) {
      const currentDateValues = this.currentDate.split('-')
      let years = 0
      let months = 0
      if (date.year) {
        years = date.year - currentDateValues[0]
      }
      if (date.month) {
        months = date.month - currentDateValues[1]
      }
      this.$refs.calendar.move(years * 12 + months)
    }
  }
}

</script>
