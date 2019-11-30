<template>
  <v-dialog
    v-model="isDialogOpened"
    width="400px"
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
            v-validate="'required'"
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
            @change="() => selectedRepeatOn.length !== 0 ? selectedRepeatOn = [] : null"
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
          v-if="errors.collect('selectedRepeatOn').length !== 0"
          class="red--text pl-1"
        >
          {{ errors.collect('selectedRepeatOn')[0] }}
        </span>
        <v-item-group
          v-model="selectedRepeatOn"
          v-validate="'required'"
          multiple
          :error-messages="errors.collect('selectedRepeatOn')"
          data-vv-name="selectedRepeatOn"
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
          v-if="errors.collect('selectedRepeatOn').length !== 0"
          class="red--text pl-1"
        >
          {{ errors.collect('selectedRepeatOn')[0] }}
        </span>
        <v-radio-group
          v-model="selectedRepeatOn"
          v-validate="'required'"
          hide-details
          :error-messages="errors.collect('selectedRepeatOn')"
          data-vv-name="selectedRepeatOn"
        >
          <v-radio
            v-for="option in repeatOnMonthOptions"
            :key="option.id"
            :label="option.label"
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
          >
            <span>
              <v-radio
                label="Never"
                value="never"
              />
            </span>
            <span class="mt-1">
              <v-radio
                label="On"
                value="on"
              />
            </span>
            <span class="mt-1 mb-1">
              <v-radio
                label="After"
                value="after"
              />
            </span>
          </v-radio-group>
          <div
            v-if="selectedEndsOn === 'after'"
            class="py-3 mr-5 pr-5 d-flex align-center"
          >
            <v-text-field
              v-model="selectedOccurrences"
              v-validate="'required'"
              single-line
              outline
              label="occurrences"
              hide-details
              required
              :error-messages="errors.collect('occurrences')"
              data-vv-name="occurrences"
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
        { id: 1, label: 'First Monday on each following month' },
        { id: 2, label: 'Day 2 on each following month' }
      ],
      intervalUnits: ['Day(s)', 'Week(s)', 'Month(s)', 'Year(s)'],
      endsOnOptions: ['Never', 'On', 'After'],

      selectedIntervalUnit: 'Day(s)',
      selectedIntervalValue: '',
      selectedRepeatOn: [],
      selectedEndsOn: '',
      selectedOccurrences: '',

      isValid: true,
      isDialogOpened: true
    }
  },
  methods: {
    cancel () {
      this.$emit('cancel', false)
      this.isDialogOpened = false
      this.formData = {}
    },
    save () {
      const formData = {
        interval: this.selectedIntervalUnit,
        intervalValue: this.selectedIntervalValue,
        repeatOn: this.selectedRepeatOn,
        endsOn: this.selectedEndsOn,
        occurrences: this.selectedOccurrences
      }

      this.$emit('save', formData)
    }
  }
}
</script>

<style scoped>
  .input-text-field {
    max-width: 150px;
  }
  .compact {
    transform: scale(0.7);
    transform-origin: left;
  }
</style>
