<template>
  <div>
    <div>
      <v-checkbox
        v-model="isChecked"
        label="Recurring Event"
      />
    </div>

    <div :class="isChecked ? 'enable-area' : 'disable-area'">
      <div class="caption font-weight-bold grey--text">
        REPEAT EVERY
      </div>
      <a
        class="caption"
        @click="selectDate"
      >
        {{ selectedDate ? selectedDate : 'Select Date' }}
      </a>
    </div>

    <EventSettings
      :is-dialog-opened="isDialogOpened"
      :schedule="schedule"
      @save="save"
      @cancel="cancel"
    />
  </div>
</template>

<script>
import { get } from 'lodash'
import moment from 'moment'
import EventSettings from './EventSettings'

export default {
  name: 'EventSettingsModal',
  components: {
    EventSettings
  },
  props: {
    schedule: {
      type: Object,
      default: null
    }
  },
  data () {
    let edit = get(this, 'schedule.uuid', null) !== null
    return {
      isDialogOpened: false,
      isChecked: edit,
      selectedDate: edit ? get(this.schedule, 'description') : ''
    }
  },
  methods: {
    selectDate () {
      this.isDialogOpened = true
    },
    save (params) {
      const currentDate = moment()

      switch (params.interval_unit) {
        case 'Week(s)':
          const weekDays = params.repeat_on.map(v => v.text)
          if (params.ends_on === 'after') {
            const daysLeft = params.occurrences * params.interval_value * 7
            const endDate = currentDate.add(daysLeft, 'd')

            this.selectedDate = `${weekDays.join(', ')}, until ${endDate.format('MMMM Do, YYYY')}`
          } else {
            this.selectedDate = `Every ${weekDays.join(', ')}`
          }
          break

        case 'Month(s)':
          const description = params.repeat_on[0].text
          if (params.ends_on === 'after') {
            const monthsLeft = params.occurrences * params.interval_value
            const endDate = currentDate.add(monthsLeft, 'M')

            this.selectedDate = `${description}, util ${endDate.format('MMMM Do, YYYY')}`
          } else {
            this.selectedDate = description
          }
          break

        case 'Year(s)':
          if (params.ends_on === 'after') {
            const yearsLeft = params.occurrences * params.interval_value
            const endDate = currentDate.add(yearsLeft, 'Y')

            this.selectedDate = `Every ${params.interval_value} year(s),
              until ${endDate.format('MMMM Do, YYYY')}`
          } else {
            this.selectedDate = `Every ${params.interval_value} year(s)`
          }
          break

        default:
          break
      }
      this.$emit('save', {
        ...params,
        description: this.selectedDate
      })
      this.isDialogOpened = false
    },
    cancel () {
      this.isDialogOpened = false
    }
  }
}
</script>

<style scoped>
.disable-area {
  pointer-events: none;
  opacity: 0.5;
}
.disable-area a {
  color: grey;
}
.enable-area a {
  text-decoration: underline;
}
</style>
