<template>
  <v-container
    v-if="!isLoading"
    fill-height
  >
    <user-profile
      :user="currentUser"
      is-current-user
      :company="user.company"
    />
  </v-container>
</template>

<script>
import UserProfile from '~/components/users/UserProfile.vue'
import { mapGetters } from 'vuex'

export default {
  components: {
    UserProfile
  },
  computed: {
    ...mapGetters('users', { user: 'item' }),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters(['currentUser']),
    isCurrentUserAdmin () {
      return this.currentUser && this.currentUser.level < 5
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('currentUser/getCurrentUser', { params: { include: 'teams.users' } }).then(() => {
      Promise.all([
        vm.$store.dispatch('users/getItem', { params: { id: vm.currentUser.id, include: 'teams.users,company.users' } })
      ]).then(() => {
        vm.$store.dispatch('page/setTitle', vm.currentUser.name)
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
        .catch((error) => {console.log(error)})
        .then(() => {
          vm.$store.dispatch('page/setLoading', false)
        })
    })
  }
}
</script>
