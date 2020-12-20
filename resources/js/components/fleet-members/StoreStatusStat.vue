<template>
  <v-layout
    column
    class="pa-4"
    justify-center
    align-center
  >
    <v-flex
      class="display-3"
      :class="color + '--text'"
    >
      {{ value }}
    </v-flex>
    <v-flex class="grey--text text-uppercase font-weight-bold">
      {{ label }}
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  props: {
    label: { type: String, default: '' },
    value: { type: Number, default: 0 },
    color: { type: String, default: 'grey' }
  }
}
</script>
