<template>
  <div>
    <div>
      <basic-information
        @save="saveMember"
        @delete="deleteMember"
        @cancel="onCancel"
      />
    </div>
    <div>
      <payments />
    </div>
  </div>
</template>
<script>
import BasicInformation from './BasicInformation'
import Payments from './Payments'

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    Payments
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
