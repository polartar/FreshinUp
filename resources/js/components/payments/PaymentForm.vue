<template>
  <form class="py-2 px-2">
    <v-progress-linear
      v-if="isLoading"
      indeterminate
    />
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
        single-line
        outline
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
        @click="onCancel"
      >
        Cancel
      </v-btn>
      <v-btn
        :loading="isLoading"
        depressed
        color="primary"
        @click="save"
      >
        Save changes
      </v-btn>
    </v-flex>
  </form>
</template>

<script>
import DateTimePicker from '../DateTimePicker'
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_PAYMENT = {
  id: '',
  name: '',
  amount_money: '',
  description: '',
  due_date: ''
}

export default {
  components: { DateTimePicker },
  mixins: [MapValueKeysToData],
  props: {
    isLoading: { type: Boolean, default: false },
    value: { type: Object, default: () => DEFAULT_PAYMENT }
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
