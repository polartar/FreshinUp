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
    if (next) next()
  }
}
</script>
