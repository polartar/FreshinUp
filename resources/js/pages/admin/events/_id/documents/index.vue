<template>
  <div>
    <v-layout
      row
      align-center
      pt-3
    >
      <v-btn
        flat
        small
        @click="backToEventDetails"
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to Events Details</span>
        </div>
      </v-btn>
    </v-layout>
    <v-layout
      row
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        Coming soon
      </h2>
    </v-layout>
    <v-divider />
    <br>
    <v-layout
      row
      pa-2
    />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  layout: 'admin',
  data () {
    return {
    }
  },
  computed: {
    ...mapGetters('page', ['isLoading']),
    ...mapGetters('events', { event: 'item' })
  },
  methods: {
    ...mapActions('page', {
      setPageLoading: 'setLoading'
    }),
    backToEventDetails () {
      this.$router.push({ path: '/admin/events/' + this.event.uuid + '/edit' })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.setPageLoading(true)
    const eventUuid = to.path.split('/')[3]

    let eventParams = { id: eventUuid }

    Promise.all([
      vm.$store.dispatch('events/getItem', { params: eventParams })
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>

<style scoped>
.back-btn-inner{
  color: #fff;
  display: flex;
  align-items: center;
  font-size: 13px;
}
.back-btn-inner span{
  margin-left: 10px;
  font-weight: bold;
  text-transform: initial;
}
.back-btn-inner .v-icon{
  font-size: 16px;
}
.service-summary{
  margin-top: 15px;
}
</style>
