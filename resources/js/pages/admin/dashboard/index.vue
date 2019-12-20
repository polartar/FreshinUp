<template>
  <v-container v-if="!isLoading">
    <v-layout row>
      <v-flex
        xs12
        pa-2
      >
        <h2 class="f-page__title f-page__title--admin">
          {{ pageTitle }}
        </h2>
        <p class="white--text">
          We need to walk you through these steps before you can {{ youCanText }}.
        </p>
      </v-flex>
    </v-layout>
    <v-layout
      v-show="isSupplier"
    >
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
          :nav-to="editCompanyRoute"
          :icon="{
            name: 'icon-companies',
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
            title: '3. Submit Your Fleet',
            description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
            button: 'Take me there!'
          }"
          :nav-to="'/admin/fleet-members/new'"
          :icon="{
            name: 'icon-trucks',
            size: 175
          }"
        />
      </v-flex>
    </v-layout>
    <v-layout
      v-show="!isSupplier"
    >
      <v-flex
        pa-2
        xs12
        sm3
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
            size: 145
          }"
        />
      </v-flex>
      <v-flex
        pa-2
        xs12
        sm3
      >
        <steps-card
          :content="{
            title: '2. Register Your Company',
            description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
            button: 'Take me there!'
          }"
          :nav-to="editCompanyRoute"
          :icon="{
            name: 'icon-companies',
            size: 145
          }"
        />
      </v-flex>
      <v-flex
        pa-2
        xs12
        sm3
      >
        <steps-card
          :content="{
            title: '3. Submit A Venue',
            description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
            button: 'Take me there!'
          }"
          :nav-to="'/admin/venues/new'"
          :icon="{
            name: 'icon-venues',
            size: 145
          }"
        />
      </v-flex>
      <v-flex
        pa-2
        xs12
        sm3
      >
        <steps-card
          :content="{
            title: '4. Book An Event',
            description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis tincidunt magna, sed pharetra neque. Mauris ultrices felis quis elit aliquet, dapibus fringilla.',
            button: 'Take me there!'
          }"
          :nav-to="'/admin/events/new'"
          :icon="{
            name: 'icon-events',
            size: 145
          }"
        />
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
import { mapGetters } from 'vuex'
import StepsCard from '~/components/dashboard/StepsCard.vue'

export default {
  layout: 'admin',
  name: 'Dashboard',
  components: {
    StepsCard
  },
  data () {
    return {
      pageTitle: 'Welcome to Food Fleet!'
    }
  },
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('page', ['isLoading']),
    editUserRoute () {
      if (this.currentUser && this.currentUser.company_id) {
        return '/admin/users/' + this.currentUser.id + '/edit'
      } else {
        return '/admin/dashboard'
      }
    },
    editCompanyRoute () {
      if (this.currentUser && this.currentUser.company_id) {
        return '/admin/companies/' + this.currentUser.company_id + '/edit'
      } else {
        return '/admin/dashboard'
      }
    },
    isSupplier () {
      return this.currentUser.type !== 2
    },
    youCanText () {
      return this.isSupplier ? 'join our fleet' : 'book an event'
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('currentUser/getCurrentUser').then(() => {
      vm.$store.dispatch('page/setLoading', false)
    })
  }
}
</script>
