<template>
  <div>
    <basic-information
      @save="saveMember"
      @delete="deleteMember"
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
    beforeRouteEnterOrUpdate (vm, to, from, next) {},

    saveMember (item) {},

    deleteMember (item) {}
  }
}
</script>
