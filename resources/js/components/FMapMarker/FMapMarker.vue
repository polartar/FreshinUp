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
      // coordinates: { type: Object },
      // color: { type: String }
    }
  }
</script>
