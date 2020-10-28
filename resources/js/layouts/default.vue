<template>
  <div
    v-if="currentUser"
    class="page-layout"
  >
    <slot name="navigation-drawer">
      <v-navigation-drawer
        v-model="isDrawerOpen"
        :clipped="isClipped"
        disable-resize-watcher
        stateless
        app
        dark
        width="220px"
      >
        <div
          v-if="navDrawerLogo"
          class="logo-container"
        >
          <img :src="logo">
        </div>
        <navigation-drawer-list
          :items="items"
          :no-actions="navDrawerNoActions"
        />
      </v-navigation-drawer>
    </slot>
    <slot name="navigation-top">
      <v-toolbar
        fixed
        app
        flat
        color="white"
      >
        <v-toolbar-title>
          <v-flex class="primary--text">
            <template v-if="isAdmin">
              Food Fleet Admins
            </template>
            <img
              v-else
              :src="logo"
            >
          </v-flex>
        </v-toolbar-title>
        <v-spacer />
        <v-menu
          v-show="notifications"
          left
          offset-y
          max-width="320"
          origin="right top"
          :disabled="!notifications.length"
        >
          <v-btn
            slot="activator"
            icon
            flat
            :disabled="!notifications.length"
          >
            <v-badge
              color="red"
              overlap
            >
              <span
                v-if="notifications.length"
                slot="badge"
              >
                {{ notifications.length }}
              </span>
              <v-icon
                medium
                color="grey darken-2"
              >
                notifications
              </v-icon>
            </v-badge>
          </v-btn>

          <notification-menu />
        </v-menu>
        <v-menu
          v-if="currentUser"
          left
          offset-y
          content-class="user-menu"
          max-width="300"
        >
          <v-btn
            slot="activator"
            icon
            flat
            class="avatar"

          >
            <f-user-avatar :user="currentUser" :size="48" color="primary"/>
          </v-btn>

          <user-menu
            :user="currentUser"
            :user-is-admin="isCurrentUserAdmin"
            :menu-items="userMenuItems"
            @signout="signout"
          />
        </v-menu>
      </v-toolbar>
    </slot>
    <v-content class="main-page-container">
      <v-container
        v-if="pageTitle"
        fluid
      >
        <h1 class="page-title white--text">
          {{ pageTitle }}
        </h1>
      </v-container>

      <v-progress-linear
        v-if="isLoading"
        :indeterminate="true"
      />
      <router-view v-show="!isLoading" />
      <v-snackbar
        v-model="isVisible"
        color="error"
        :timeout="6000"
        top
      >
        {{ errorMessages }}
        <v-btn
          dark
          flat
          @click="setErrorVisibility(false)"
        >
          Close
        </v-btn>
      </v-snackbar>
      <v-snackbar
        v-model="isMessageVisible"
        color="success"
        :timeout="6000"
        top
      >
        {{ message }}
        <v-btn
          dark
          flat
          @click="setMessageVisibility(false)"
        >
          Close
        </v-btn>
      </v-snackbar>
    </v-content>
    <slot name="footer">
      <fresh-bus-footer />
    </slot>
  </div>
</template>

<script>
import '../../fonts/css/fontello.css'
import { mapState, mapGetters, mapActions } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import UserMenu from 'fresh-bus/components/layout/UserMenu.vue'
import NavigationDrawerList from 'fresh-bus/components/layout/NavigationDrawerList.vue'
import NotificationMenu from 'fresh-bus/components/layout/NotificationMenu.vue'
import FreshBusFooter from 'fresh-bus/components/Footer.vue'
import FUserAvatar from '@freshinup/core-ui/src/components/FUserAvatar'

const generalErrorMessageFields = createHelpers({
  getterType: 'generalErrorMessages/getField',
  mutationType: 'generalErrorMessages/updateField'
}).mapFields

const generalMessageFields = createHelpers({
  getterType: 'generalMessage/getField',
  mutationType: 'generalMessage/updateField'
}).mapFields

const navigationAdminFields = createHelpers({
  getterType: 'navigationAdmin/getField',
  mutationType: 'navigationAdmin/updateField'
}).mapFields

export default {
  components: {
    UserMenu,
    NotificationMenu,
    NavigationDrawerList,
    FreshBusFooter,
    FUserAvatar
  },
  data: () => ({
    isAdmin: false,
    navDrawerLogo: false,
    navDrawerNoActions: false
  }),
  computed: {
    ...generalErrorMessageFields([
      'isVisible'
    ]),
    ...navigationAdminFields({
      isDrawerOpen: 'isDrawerOpen',
      isClipped: 'isClipped'
    }),
    ...generalMessageFields({
      isMessageVisible: 'isVisible'
    }),
    ...mapState('navigation', {
      items: 'drawerItems',
      logo: 'logo'
    }),
    ...mapGetters('generalErrorMessages', {
      errorMessages: 'errorMessages'
    }),
    ...mapGetters('generalMessage', {
      message: 'message'
    }),
    ...mapGetters(['currentUser']),
    ...mapGetters('currentUser', {
      isCurrentUserAdmin: 'isAdmin'
    }),
    ...mapState('navigation', {
      userMenuItems: state => state.userMenuItems
    }),
    ...mapGetters(['currentUser']),
    ...mapGetters('userNotifications', { 'notifications': 'unacknowledged' }),
    ...mapGetters('page', {
      isLoading: 'isLoading',
      pageTitle: 'title'
    })
  },
  methods: {
    ...mapActions('generalErrorMessages', {
      setErrorVisibility: 'setVisibility'
    }),
    ...mapActions('generalMessage', {
      setMessageVisibility: 'setVisibility'
    }),
    signout () {
      this.$auth.logout({
        redirect: 'auth'
      })
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    Promise.all([
      vm.$store.dispatch('currentUser/getCurrentUser')
    ]).then(() => {
      vm.$store.dispatch('page/setLoading', false)
      if (next) next()
    })
  }
}
</script>

<style scoped>
  /deep/ .v-avatar.primary {
    width: 48px;
    height: 48px;
    background-color: tomato;;
  }
  .main-page-container {
    background-color: transparent;
  }
  /deep/ .main-page-container .v-content__wrap {
    background: url('/images/bg-header.jpg') no-repeat top left;
    background-size: auto 350px;
  }

  .page-title {
    color: white;
  }

  .logo-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-items: center;
    background-color: #FCFBF9;
    height: 64px;
  }
  .logo-container img {
    width: 180px
  }

  .theme--dark.v-navigation-drawer {
    background-color: #192530;
  }

  /deep/ .v-navigation-drawer .v-list .v-list__tile {
    padding: 0 24px !important;
  }
  /deep/ .v-navigation-drawer .v-list .v-list__tile .v-list__tile__title {
  }
  /deep/ .v-navigation-drawer .v-list .v-list__tile.v-list__tile--active,
  /deep/ .v-navigation-drawer .v-list .v-list__tile:hover {
    background-color: var(--v-primary-base);
    color: white !important
  }

  /deep/ .v-toolbar__content {
    padding-left: 10px;
  }
</style>
