<template>
  <MglMarker
    v-bind="$attrs"
  />
</template>
<script>
import { MglMarker } from 'vue-mapbox'
export default {
  components: {
    MglMarker
  },
  props: {
    // ** DO NOT UNCOMMENT **
    // This strictly serve as documentations for $attrs
    // offset: {
    //   type: [Object, Array],
    //   default: () => [0, 0]
    // },
    // coordinates: {
    //   type: Array,
    //   required: true
    // },
    // color: {
    //   type: String
    // },
    // anchor: {
    //   type: String,
    //   default: "center"
    // },
    // draggable: {
    //   type: Boolean,
    //   default: false
    // }
  }
}
</script>
