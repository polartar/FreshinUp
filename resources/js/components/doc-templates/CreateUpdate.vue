<template>
  <div>
    Create Update doc template view
  </div>
</template>

<script>
export default {
  layout: 'admin',

  methods: {
    beforeRouteEnterOrUpdate (vm, to, from, next) {
      vm.$store.dispatch('page/setLoading', false)
      next && next()
    }
  }
}
</script>

<style scoped>

</style>
