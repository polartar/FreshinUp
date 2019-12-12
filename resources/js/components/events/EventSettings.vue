<template>
  <v-dialog
    v-model="isDialogOpened"
    width="100%"
  >
    <div class="white mb-2">
      <div class="pa-2 p-md-2">
        <div class="pa-2 d-inline-block font-weight-bold grey--text">
          Recurring Event Settings
        </div>
        <v-chip
          close
          x-small
          class="right"
          @click="() => isDialogOpened = false"
        >
          Close
        </v-chip>
      </div>

      <v-divider />

      <div>
        <div class="mt-4 px-3 py-2 caption font-weight-bold grey--text">
          REPEAT EVERY
          <span
            v-if="errors.collect('intervalValue').length !== 0"
            class="red--text pl-1"
          >
            {{ errors.collect('intervalValue')[0] }}
          </span>
        </div>
        <div class="px-4 f-flex align-center no-wrap d-flex compact">
          <v-text-field
            v-model="selectedIntervalValue"
            v-validate="{ required: true, regex: /^\d+$/ }"
            single-line
            outline
            hide-details
            :error-messages="errors.collect('intervalValue')"
            data-vv-name="intervalValue"
            class="input-text-field d-inline-block mr-3"
          />

          <v-select
            v-model="selectedIntervalUnit"
            v-validate="'required'"
            :items="intervalUnits"
            single-line
            outline
            hide-details
            dense
            data-vv-name="selectedIntervalUnit"
            class="d-inline-flex mr-5"
            @change="() => resetRepeatOnSelections()"
          />
        </div>
      </div>

      <div
        v-if="selectedIntervalUnit === 'Week(s)'"
        class="mt-3 px-3 py-2 caption font-weight-bold grey--text"
      >
        <span>
          REPEAT ON
        </span>
        <span
          v-if="errors.collect('selectedRepeatOnWeek').length !== 0"
          class="red--text pl-1"
        >
          {{ errors.collect('selectedRepeatOnWeek')[0] }}
        </span>
        <v-item-group
          v-model="selectedRepeatOnWeek"
          v-validate="'required'"
          multiple
          :error-messages="errors.collect('selectedRepeatOnWeek')"
          data-vv-name="selectedRepeatOnWeek"
        >
          <v-item
            v-for="option in repeatOnWeekOptions"
            :key="option.id"
            v-slot:default="{ active, toggle }"
          >
            <v-btn
              small
              fab
              depressed
              class="white--text mr-0 pa-0"
              :color="active ? 'primary' : 'grey'"
              :input-value="active"
              @click="toggle"
            >
              {{ option.text.substring(0, 1) }}
            </v-btn>
          </v-item>
        </v-item-group>
      </div>

      <div
        v-else-if="selectedIntervalUnit === 'Month(s)'"
        class="mt-1 px-3 py-2 caption font-weight-bold grey--text"
      >
        REPEAT ON
        <span
          v-if="errors.collect('selectedRepeatOnMonth').length !== 0"
          class="red--text pl-1"
        >
          {{ errors.collect('selectedRepeatOnMonth')[0] }}
        </span>
        <v-radio-group
          v-model="selectedRepeatOnMonth"
          v-validate="'required'"
          hide-details
          :error-messages="errors.collect('selectedRepeatOnMonth')"
          data-vv-name="selectedRepeatOnMonth"
        >
          <v-radio
            v-for="option in repeatOnMonthOptions"
            :key="option.id"
            :label="option.text"
            :value="option.id"
          />
        </v-radio-group>
      </div>

      <div class="px-3 mt-3 caption font-weight-bold grey--text">
        <div>
          ENDS ON
          <span
            v-if="errors.collect('selectedEndsOn').length !== 0"
            class="red--text pl-1"
          >
            {{ errors.collect('selectedEndsOn')[0] }}
          </span>
        </div>
        <div class="d-flex align-end">
          <v-radio-group
            v-model="selectedEndsOn"
            v-validate="'required'"
            hide-details
            :error-messages="errors.collect('selectedEndsOn')"
            data-vv-name="selectedEndsOn"
            class="pb-3 ends-on-selection"
          >
            <span>
              <v-radio
                label="On"
                value="on"
              />
            </span>
            <span class="mt-3">
              <v-radio
                label="After"
                value="after"
              />
            </span>
          </v-radio-group>
          <div
            v-if="selectedEndsOn === 'after'"
          >
            <v-text-field
              v-model="selectedOccurrences"
              v-validate="{ required: true, regex: /^\d+$/ }"
              single-line
              outline
              label="occurrences"
              hide-details
              required
              :error-messages="errors.collect('occurrences')"
              data-vv-name="selectedEndsOn"
              class="input-text-field compact"
            />
          </div>
        </div>
      </div>

      <div class="px-3 pb-1 mt-3 caption font-weight-bold grey--text d-flex">
        <v-card-actions>
          <v-btn
            block
            class="font-weight-bold"
            @click="cancel"
          >
            Cancel
          </v-btn>

          <v-btn
            color="primary"
            block
            class="font-weight-bold"
            :disabled="!isValid"
            @click="whenValid(save)"
          >
            Save Changes
          </v-btn>
        </v-card-actions>
      </div>
    </div>
  </v-dialog>
</template>

<script>
import Validate from 'fresh-bus/components/mixins/Validate'

export default {
  name: 'EventSettings',
  mixins: [Validate],
  props: {
    isDialogOpened: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      repeatOnWeekOptions: [
        { id: 1, text: 'Sunday' },
        { id: 2, text: 'Monday' },
        { id: 3, text: 'Tuesday' },
        { id: 4, text: 'Wednesday' },
        { id: 5, text: 'Thursday' },
        { id: 6, text: 'Friday' },
        { id: 7, text: 'Saturday' }
      ],
      repeatOnMonthOptions: [
        { id: 1, text: 'First Monday on each following month' },
        { id: 2, text: 'Day 2 on each following month' }
      ],
      intervalUnits: ['Week(s)', 'Month(s)', 'Year(s)'],
      endsOnOptions: ['On', 'After'],

      selectedIntervalUnit: 'Week(s)',
      selectedIntervalValue: '',
      selectedRepeatOnWeek: [],
      selectedRepeatOnMonth: '',
      selectedEndsOn: '',
      selectedOccurrences: '',

      isValid: true
    }
  },
  methods: {
    cancel () {
      this.$emit('cancel', false)
    },
    resetRepeatOnSelections () {
      this.selectedRepeatOnWeek = []
      this.selectedRepeatOnMonth = ''
    },
    save () {
      let selectedRepeatOn

      switch (this.selectedIntervalUnit) {
        case 'Week(s)':
          selectedRepeatOn = this.repeatOnWeekOptions.filter(day => {
            return this.selectedRepeatOnWeek.find(value => {
              return (value + 1) === day.id
            }) >= 0
          })
          break
        case 'Month(s)':
          selectedRepeatOn = this.repeatOnMonthOptions.filter(option => {
            return this.selectedRepeatOnMonth === option.id
          })
          break
        default:
          break
      }

      const formData = {
        intervalUnit: this.selectedIntervalUnit,
        intervalValue: this.selectedIntervalValue,
        repeatOn: selectedRepeatOn,
        endsOn: this.selectedEndsOn,
        occurrences: this.selectedOccurrences
      }
      this.$emit('save', formData)
    }
  }
}
</script>

<style scoped>
  .compact {
    transform: scale(0.6);
    transform-origin: left;
  }
  .ends-on-selection {
    max-width: 90px;
  }
  .input-text-field {
    max-width: 200px;
  }
</style>
