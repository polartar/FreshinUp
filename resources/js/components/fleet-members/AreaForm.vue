<template>
  <form>
    <v-flex
      xs12
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
  </form>
</template>
<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
export const DEFAULT_STORE = {
  id: 0,
  name: '',
  radius: 0,
  state: '',
  store_uuid: ''
}
export default {
  mixins: [MapValueKeysToData],
  data () {
    return DEFAULT_STORE
  }
}
</script>
