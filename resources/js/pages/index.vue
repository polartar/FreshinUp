<template>
  <v-content>
    Welcome to Food Fleet
  </v-content>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(false)
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    })
  }
}
</script>
