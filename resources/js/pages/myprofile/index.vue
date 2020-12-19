<template>
  <v-container
    v-if="!isLoading"
    fill-height
  >
    <v-layout
      row
      wrap
    >
      <v-flex
        xs12
        mb-4
      >
        <basic-information
          :value="user"
        />
      </v-flex>
      <v-flex
        xs12
        mb-4
      >
        <notification-settings />
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import UserProfile from '~/components/users/UserProfile.vue'
  import { mapGetters } from 'vuex'
  import NotificationSettings from '../../components/users/NotificationSettings'
  import BasicInformation from '../../components/users/BasicInformation'

  export default {
    components: {
      UserProfile,
      NotificationSettings,
      BasicInformation
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
      vm.$store.dispatch('currentUser/getCurrentUser', {
        params: { include: 'teams.users' } })
        .then(() => {
        Promise.all([
          vm.$store.dispatch('users/getItem', {
            params: {
              id: vm.currentUser.id,
              include: 'teams.users,company.users,company'
            }
          })
        ])
          .then(() => {
          vm.$store.dispatch('page/setTitle', vm.currentUser.name)
          if (next) next()
        })
          .catch((error) => { console.error(error) })
          .then(() => {
            vm.$store.dispatch('page/setLoading', false)
          })
      })
    }
  }
</script>
