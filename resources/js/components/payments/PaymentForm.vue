<template>
  <form class="py-2 px-2">
    <v-progress-linear
      v-if="isLoading"
      indeterminate
    />
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Event
      </div>
      <v-select
        v-model="event_uuid"
        v-validate="'required'"
        :error-messages="errors.collect('event_uuid')"
        data-vv-name="event_uuid"
        :items="events"
        item-text="name"
        item-value="uuid"
        single-line
        solo
        outline
        flat
      />
    </v-flex>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Payment Name
      </div>
      <v-text-field
        v-model="name"
        single-line
        outline
      />
    </v-flex>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Amount
      </div>
      <v-text-field
        v-model="amount_money"
        v-validate="'required'"
        :error-messages="errors.collect('amount_money')"
        type="number"
        single-line
        outline
        data-vv-name="amount_money"
      />
    </v-flex>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Description
      </div>
      <v-text-field
        v-model="description"
        single-line
        outline
      />
    </v-flex>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Due Date
      </div>
      <date-time-picker
        v-model="due_date"
        only-date
        format="YYYY-MM-DD"
        formatted="MM-DD-YYYY"
        input-size="lg"
        label="Select date"
        :color="$vuetify.theme.primary"
        :button-color="$vuetify.theme.primary"
      />
    </v-flex>
    <v-flex class="py-3 d-flex justify-space-between">
      <v-btn
        color="grey"
        depressed
        class="white--text"
        @click="onCancel"
      >
        Cancel
      </v-btn>
      <v-btn
        :loading="isLoading"
        depressed
        color="primary"
        @click="whenValid(save)"
      >
        Save changes
      </v-btn>
    </v-flex>
  </form>
</template>

<script>
import DateTimePicker from '../DateTimePicker'
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

import Validate from 'fresh-bus/components/mixins/Validate'
export const DEFAULT_PAYMENT = {
  id: '',
  name: '',
  amount_money: '',
  description: '',
  due_date: '',
  store_uuid: '',
  status_id: 1,
  event_uuid: ''
}

export default {
  components: { DateTimePicker },
  mixins: [MapValueKeysToData, Validate],
  props: {
    isLoading: { type: Boolean, default: false },
    value: { type: Object, default: () => DEFAULT_PAYMENT },
    events: { type: Array, default: () => [] }
  },
  data () {
    return {
      ...DEFAULT_PAYMENT
    }
  },
  methods: {
    onCancel () {
      this.$emit('cancel')
    }
  }
}
</script>

<style scoped>

</style>
