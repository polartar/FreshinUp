<template>
  <div>
    <basic-information
      @save="saveMember"
      @delete="deleteMember"
      @cancel="onCancel"
    />
  </div>
</template>
<script>
import BasicInformation from './BasicInformation'

export default {
  layout: 'admin',
  components: {
    BasicInformation
  },
  methods: {
    saveMember (item) {},

    deleteMember (item) {},

    onCancel () {
      this.$router.push('/admin/fleet-members')
    }
  },

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []
    if (id !== 'new') {
    }
    vm.$store.dispatch('page/setLoading', true)
    Promise.all(promises)
      .then(() => {})
      .catch((error) => {
        console.error(error)
      })
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>
