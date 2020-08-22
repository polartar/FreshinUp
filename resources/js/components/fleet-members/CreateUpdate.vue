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
    saveMember (item) {},

    deleteMember (item) {}
  },

  beforeRouteEnterOrUpdate (vm, to, from, next) {
    if (next) next()
  }
}
</script>
