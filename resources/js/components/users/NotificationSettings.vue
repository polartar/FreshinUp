<template>
  <v-card>
    <v-card-title>
      <h3 class="grey--text">
        Notification settings
      </h3>
      <v-progress-linear
        v-if="isLoading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-layout
      row
      wrap
      class="pa-4"
      align-center
    >
      <div>Coming Soon</div>
    </v-layout>
  </v-card>
</template>
<script>
export default {
  props: {
    isLoading: { type: Boolean, default: false }
  }
}
</script>
