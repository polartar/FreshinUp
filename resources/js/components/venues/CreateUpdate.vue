<template>
  <div class="pa-4">
    <div>
      <v-btn
        flat
        small
        class="mx-0"
      >
        <div class="d-flex align-content-center white--text">
          <v-icon>fas fa-arrow-left</v-icon>
          <span
            class="mx-3"
            style="line-height: 1.60rem;"
          >Return to Venues list</span>
        </div>
      </v-btn>
    </div>
    <div class="d-flex justify-space-between align-content-center my-3">
      <div class="white--text headline">{{ pageTitle }}</div>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <v-select
          v-model="status"
          :items="['active', 'inactive']"
          single-line
          solo
          flat
          hide-details
        />
      </v-flex>
    </div>
    <div class="mt-5">
      <BasicInformation />
    </div>
  </div>
</template>
<script>
import BasicInformation from './BasicInformation'

export default {
  layout: 'admin',
  components: {
    BasicInformation
  },
  data () {
    return {
      status: 'active'
    }
  },
  computed: {
    pageTitle () {
      return this.isNew ? 'New Fleet Member' : 'Fleet Member Details'
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    const id = to.params.id || 'new'
    const promises = []
    // const params = { id }

    if (id !== 'new') {}

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
