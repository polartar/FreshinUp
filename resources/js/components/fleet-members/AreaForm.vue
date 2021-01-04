<template>
  <form class="py-2 px-2">
    <v-progress-linear
      v-if="isLoading"
      indeterminate
    />
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Area Name
      </div>
      <v-text-field
        v-model="name"
        v-validate="'required'"
        :error-messages="errors.collect('name')"
        data-vv-name="name"
        placeholder="Enter the area name"
        single-line
        outline
      />
    </v-flex>
    <v-flex>
      <div class="text-uppercase grey--text font-weight-bold">
        Radius
      </div>
      <v-slider
        v-model="radius"
        :min="0"
        :max="100"
        thumb-label
      />
    </v-flex>
    <v-flex>
      <v-layout
        row
        class="justify-space-between grey--text"
      >
        <span class="font-weight-bold">0</span>
        <span class="font-weight-bold">100</span>
      </v-layout>
    </v-flex>
    <v-flex class="py-3 d-flex justify-space-between">
      <v-btn
        depressed
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

import MapValueKeysToData from '../../mixins/MapValueKeysToData'
import Validate from 'fresh-bus/components/mixins/Validate'
export const DEFAULT_AREA = {
  id: '',
  name: '',
  radius: '',
  state: '',
  store_uuid: ''
}
export default {
  mixins: [MapValueKeysToData, Validate],
  props: {
    isLoading: { type: Boolean, default: false },
    // Override value prop to give default object mapping
    value: { type: Object, default: () => DEFAULT_AREA }
  },
  data () {
    return {
      ...DEFAULT_AREA
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
  >>>.v-slider__track__container, >>>.v-slider__track, >>>.v-slider__track-fill {
    height: 10px;
  }

  >>>.v-slider__track {
    left: 11px!important;
  }

  >>>.v-slider__thumb {
    height: 30px!important;
    width: 30px!important;
  }
</style>
