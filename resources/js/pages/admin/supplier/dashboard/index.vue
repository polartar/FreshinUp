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

    <v-layout v-show="!isDashboardComplete">
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
            :disabled='!isPersonalComplete'
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
            :disabled='!isPersonalComplete || !isCompanyComplete'
          />
        
        </v-flex>
           
     
    </v-layout>

    <v-layout v-show="isDashboardComplete">
        <admin-dashboard />
    </v-layout>

  </v-container>
</template>
<script>
import { mapGetters } from 'vuex'
import StepsCard from '~/components/dashboard/StepsCard.vue'
import dashboard from '~/pages/admin/dashboard/index.vue'

export default {
  layout: 'admin',
  name: 'Dashboard',
  components: {
    StepsCard,
    AdminDashboard:dashboard
  },
  data () {
    return {
      pageTitle: 'Welcome to Food Fleet!'
    }
  },
  computed: {
    ...mapGetters(['currentUser']),
    ...mapGetters('stores', { stores: 'items' }),
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
    },
    isPersonalComplete(){
      const currentUser = this.currentUser;
      return currentUser.email && currentUser.first_name && currentUser.last_name ? true : false;
    },
    isCompanyComplete(){
      const company = this.currentUser.company;
      return company.name && company.status &&  company.company_types.length ? true : false;
    },
    isDashboardComplete(){
      const store = this.stores.filter(store=>store.owner_uuid === this.currentUser.uuid)

      if(this.stores.length ===0 || store.length === 0 )
        return false;

      return this.isPersonalComplete && this.isCompanyComplete ? true : false;
    },
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('currentUser/getCurrentUser').then(() => {
    vm.$store.dispatch('page/setLoading', false)
    })
  }
}
</script>