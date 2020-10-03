<template>
  <div>
    Coming soon
  </div>
</template>

<script>
export default {

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', false)
    next && next()
  }
}
</script>

<style scoped>

</style>
