<template>
  <v-container
    v-if="!isLoading"
    fill-height
  >
    <user-profile
      :user="user"
      :admin-user="companyAdmin"
      :teams="user.teams"
      :company="user.company"
      :is-current-user-admin="isCurrentUserAdmin"
      :is-current-user="isCurrentUser"
    />
  </v-container>
</template>

<script>
import UserProfile from '~/components/users/UserProfile'
import { mapGetters } from 'vuex'

export default {
  components: {
    UserProfile
  },
  computed: {
    ...mapGetters('users', { user: 'item' }),
    ...mapGetters('companies/admin', { companyAdmin: 'items' }),
    ...mapGetters('page', ['isLoading']),
    ...mapGetters(['currentUser']),
    ...mapGetters('industryRoles', { industryRoles: 'getNames' }),
    isCurrentUserAdmin () {
      return this.currentUser && this.currentUser.level < 5
    },
    isCurrentUser () {
      return this.currentUser.id === this.user.id
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    const id = to.params.id
    Promise.all([
      vm.$store.dispatch('users/getItem', { params: { id, include: 'teams.users,company.users' } })
    ])
      .then()
      .catch(error => console.error(error))
      .then(() => {
        vm.$store.dispatch('page/setLoading', false)
        if (next) next()
      })
  }
}
</script>
