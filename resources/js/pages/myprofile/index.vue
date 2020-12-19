<template>
  <v-layout
    v-if="!isLoading"
    class="px-4"
    row
    wrap
  >
    <v-flex
      xs12
      mb-4
    >
      <basic-information
        :is-loading="loading"
        :value="currentUser"
        :levels="levels"
        :types="types"
        @input="onSave"
        @delete="onDelete"
      />
    </v-flex>
    <v-flex
      xs12
      mb-4
    >
      <notification-settings />
    </v-flex>
  </v-layout>
</template>

<script>
import { mapGetters } from 'vuex'
import NotificationSettings from '~/components/users/NotificationSettings'
import BasicInformation from '~/components/users/BasicInformation'
import get from 'lodash/get'

export default {
  components: {
    NotificationSettings,
    BasicInformation
  },
  data () {
    return {
      loading: false
    }
  },
  computed: {
    ...mapGetters('userLevels', { levels: 'items' }),
    ...mapGetters('userTypes', { types: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters(['currentUser']),
    isCurrentUserAdmin () {
      return this.currentUser && this.currentUser.level < 5
    }
  },
  methods: {
    onSave (payload) {
      this.loading = true
      this.$store.dispatch('users/patchItem', {
        params: {
          id: this.currentUser.id
        },
        data: payload
      })
        .then(() => {
          this.$store.dispatch('generalMessage/setMessage', 'Saved.')
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.loading = false
        })
    },
    onDelete () {
      this.loading = true
      this.$store.dispatch('users/deleteItem', {
        params: {
          id: this.currentUser.id
        }
      })
        .then(() => {
          this.$store.dispatch('generalMessages/setMessage', 'Deleted.')
          this.$router.push({ path: '/auth' })
        })
        .catch(error => {
          const message = get(error, 'response.data.message', error.message)
          this.$store.dispatch('generalErrorMessages/setErrors', message)
        })
        .then(() => {
          this.loading = false
        })
    }
  },
  async beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    await vm.$store.dispatch('currentUser/getCurrentUser', {
      params: { include: 'teams.users' }
    })
    vm.$store.dispatch('page/setTitle', vm.currentUser.name)
    vm.$store.dispatch('page/setLoading', false)
    const promises = []
    promises.push(vm.$store.dispatch('userLevels/getItems'))
    promises.push(vm.$store.dispatch('userTypes/getItems'))
    Promise.all(promises)
      .then(() => {})
      .catch((error) => { console.error(error) })
      .then(() => {
        if (next) next()
      })
  }
}
</script>
