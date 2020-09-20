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
    <v-flex
      xs12
      pa-2
    >
      <div class="text-xs-center">
        MAP HERE
      </div>
    </v-flex>
    <v-layout
      align-center
      justify-space-between
    >
      <v-btn
        depressed
        disabled
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
    </v-layout>
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
