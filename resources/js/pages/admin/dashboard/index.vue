<template>
  <v-container class="px-4">
    <v-layout row>
      <v-flex
        xs12
        pa-2
      >
        <h2 class="f-page__title f-page__title--admin">
          Dashboard
        </h2>
      </v-flex>
    </v-layout>
    <v-layout>
      <v-flex
        pa-2
        xs12
        sm6
      >
        <user-requests
          :items="users"
        />
      </v-flex>
      <v-flex
        pa-2
        xs12
        sm6
      >
        <company-requests
          :items="companies"
        />
      </v-flex>
    </v-layout>
    <v-dialog
      v-model="addDialog"
      max-width="500"
    >
      <add-team
        :is-platform-admin="isPlatformAdmin"
        :companies="companiesForAddTeam"
        @cancel="addDialog = false"
        @submit="onAddTeamSubmit"
      />
    </v-dialog>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex'
import { USER_TYPE } from '../../../store/modules/userTypes'
import BusDashboard from 'fresh-bus/pages/admin/index.vue'
import get from 'lodash/get'

export default {
  extends: BusDashboard,
  layout: 'admin',
  computed: {
    ...mapGetters(['currentUser'])
  },
  watch: {
    '$store.getters.currentUser' (authUser) {
      if (parseInt(get(authUser, 'type')) === USER_TYPE.SUPPLIER) {
        this.$router.push({ path: '/admin/supplier/dashboard' })
      }
    }
  },
  beforeRouteEnterOrUpdate (vm, from, to, next) {
    vm.$store.dispatch('page/setLoading', false)
    next && next()
  }
}
</script>
