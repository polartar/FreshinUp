<template>
  <v-layout
    align-center
    justify-center
  >
    <v-card>
      <v-card-text>
        Coming soon
      </v-card-text>
    </v-card>
  </v-layout>
</template>

<script>
export default {
  beforeRouteEnterOrUpdate (vm, from, to, next) {
    vm.$store.dispatch('page/setLoading', false)
    next && next()
  }
}
</script>

<style scoped>

</style>
