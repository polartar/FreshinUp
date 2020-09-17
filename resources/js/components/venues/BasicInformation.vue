<template>
  <v-card>
    <v-card-title>
      Basic Information
      <v-progress-linear
        v-if="loading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-layout pa-3>
      Contenu
    </v-layout>
    <v-divider />
    <v-card-actions class="pa-3 d-flex justify-space-between">
      Actions buttons
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props: {
    loading: { type: Boolean, default: false }
  }
}
</script>
