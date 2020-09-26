<template>
  <form class="py-2">
    <v-flex
      xs12
      px-2
    >
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Area address
      </div>
      <v-text-field
        v-model="name"
        placeholder="Enter an address"
        single-line
        outline
      />
    </v-flex>
    <v-flex
      xs12
      px-2
    >
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
    <v-flex class="py-3 d-flex justify-space-between">
      <v-btn
        depressed
        @click="onCancel"
      >
        Cancel
      </v-btn>
      <v-btn
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
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
export default {
  mixins: [MapValueKeysToData],
  data () {
    return {
      id: 0,
      name: '',
      radius: 0,
      state: '',
      store_uuid: ''
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
