<template>
  <v-container>
    <v-layout row>
      <v-flex
        xs12
        pa-2
      >
        <h2 class="f-page__title f-page__title--admin">
          Welcome to Food Fleet!
        </h2>
        <p class="white--text">
          We need to walk you through these steps before you can join our fleet.
        </p>
      </v-flex>
    </v-layout>

    <v-layout v-if="!isLoading">
      <v-flex
        pa-2
        xs12
        sm4
      >
        <steps-card
          :content="{
              title: '1. Update Your Profile',
              description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
              button: 'Take me there!'
            }"
          :nav-to="editUserRoute"
          :icon="{
              name: 'icon-users',
              size: 175
            }"
        />
      </v-flex>
      <v-flex
        pa-2
        xs12
        sm4
      >
        <steps-card
          :content="{
              title: '2. Register Your Company',
              description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
              button: 'Take me there!'
            }"
          :nav-to="companyRoute"
          :icon="{
              name: 'icon-companies',
              size: 175
            }"
          :disabled='!userProfileCompleted'
        />
      </v-flex>
      <v-flex
        pa-2
        xs12
        sm4
      >
        <steps-card
          :content="{
              title: '3. Submit Your Fleet',
              description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
              button: 'Take me there!'
            }"
          :nav-to="'/admin/supplier/fleet-members/new'"
          :icon="{
              name: 'icon-trucks',
              size: 175
            }"
          :disabled='true || !fleetAvailable'
        />
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
  import { mapGetters } from 'vuex'
  import StepsCard from '~/components/dashboard/StepsCard.vue'
  import get from 'lodash/get'

  export default {
    layout: 'admin',
    components: {
      StepsCard,
    },
    computed: {
      ...mapGetters(['currentUser']),
      ...mapGetters('suppliers/stores', { stores: 'items' }),
      ...mapGetters('page', ['isLoading']),
      fleetAvailable () {
        return this.userProfileCompleted
          && this.userCompanyCompleted
      },
      editUserRoute () {
        return '/admin/users/' + this.currentUser.id + '/edit'
      },
      companyRoute () {
        const uuid = get(this.currentUser, 'company.uuid')
        return uuid
          ? `/admin/companies/${uuid}/edit`
          : '/admin/companies/new'
      },
      userProfileCompleted () {
        const currentUser = this.currentUser
        return currentUser && currentUser.email && currentUser.first_name && currentUser.last_name
      },
      userCompanyCompleted () {
        // TODO: we might want to wait for company.status to be approved/active first
        return Boolean(get(this.currentUser, 'company'))
      },
    },
    beforeRouteEnterOrUpdate (vm, to, from, next) {
      vm.$store.dispatch('page/setLoading', true)
      vm.$store.dispatch('currentUser/getCurrentUser')
        .then()
        .catch(console.error)
        .then(() => {
          vm.$store.dispatch('page/setLoading', false)
          next && next()
        })
    }
  }
</script>
