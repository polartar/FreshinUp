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
      @save="save"
      @cancel="cancel"
    />
  </div>
</template>

<script>
import moment from 'moment'
import EventSettings from './EventSettings'

export default {
  name: 'EventSettingsModal',
  components: {
    EventSettings
  },
  data () {
    return {
      isDialogOpened: false,
      isChecked: false,
      selectedDate: '',
      formData: []
    }
  },
  methods: {
    selectDate () {
      this.isDialogOpened = true
    },
    save (formData) {
      this.formData = formData

      const currentDate = moment()

      switch (formData.intervalUnit) {
        case 'Week(s)':
          const weekDays = formData.repeatOn.map(v => v.text)
          if (formData.endsOn === 'after') {
            const daysLeft = formData.occurrences * formData.intervalValue
            const endDate = currentDate.add(daysLeft, 'd')

            this.selectedDate = `${weekDays.join(', ')}, until ${endDate.format('MMMM Do, YYYY')}`
          } else {
            this.selectedDate = `Every ${weekDays.join(', ')}`
          }
          break

        case 'Month(s)':
          const description = formData.repeatOn[0].text
          if (formData.endsOn === 'after') {
            const monthsLeft = formData.occurrences * formData.intervalValue
            const endDate = currentDate.add(monthsLeft, 'M')

            this.selectedDate = `${description}, util ${endDate.format('MMMM Do, YYYY')}`
          } else {
            this.selectedDate = description
          }
          break

        case 'Year(s)':
          if (formData.endsOn === 'after') {
            const yearsLeft = formData.occurrences * formData.intervalValue
            const endDate = currentDate.add(yearsLeft, 'Y')

            this.selectedDate = `Every ${formData.intervalValue} year(s),
              until ${endDate.format('MMMM Do, YYYY')}`
          } else {
            this.selectedDate = `Every ${formData.intervalValue} year(s)`
          }
          break

        default:
          break
      }
      this.$emit('save', {
        ...formData,
        description: this.selectedDate
      })
      this.isDialogOpened = false
    },
    cancel () {
      this.formData = []
      this.selectedDate = ''
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
