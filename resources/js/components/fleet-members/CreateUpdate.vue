<template>
  <div>
    <div>
      <basic-information
        @save="saveMember"
        @delete="deleteMember"
        @cancel="onCancel"
      />
    </div>
    <v-layout
      row
      px-2
      py-4
    >
      <v-flex>
        <Events
          :events="events"
        />
      </v-flex>
    </v-layout>
  </div>
</template>
<script>
import BasicInformation from './BasicInformation'
import Events from './Events'

export default {
  layout: 'admin',
  components: {
    BasicInformation,
    Events
  },
  data () {
    return {
      events: ['Event will populate once your restaurant is assigned.']
    }
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
