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
          :items="drawItems"
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
            <f-user-avatar
              :user="currentUser"
              :size="36"
              color="primary"
            />
          </v-btn>

          <f-user-menu
            class="ff-user-menu"
            :user="authUser"
            :user-is-admin="isCurrentUserAdmin"
            :menu-items="userMenuItems"
            :consumer-view="false"
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
import NavigationDrawerList from 'fresh-bus/components/layout/NavigationDrawerList.vue'
import NotificationMenu from 'fresh-bus/components/layout/NotificationMenu.vue'
import FreshBusFooter from 'fresh-bus/components/Footer.vue'
import FUserMenu from '@freshinup/core-ui/src/components/FUserMenu'
import FUserAvatar from '@freshinup/core-ui/src/components/FUserAvatar'
import { USER_TYPE } from '../store/modules/userTypes'

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
    FUserMenu,
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
      logo: 'logo',
      userMenuItems: 'userMenuItems'
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
    ...mapGetters('userNotifications', { 'notifications': 'unacknowledged' }),
    ...mapGetters('page', {
      isLoading: 'isLoading',
      pageTitle: 'title'
    }),
    authUser () {
      const user = this.currentUser
      return {
        ...user,
        level_name: user.type === USER_TYPE.SUPPLIER ? 'Supplier' : ''
      }
    },
    ...mapState('navigationSupplier', {
      itemsSupplier: 'items'
    }),
    drawItems () {
      return this.authUser.level_name === 'Supplier' ? this.itemsSupplier : this.items
    }
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
        redirect: '/auth'
      })
    }
  },
  watch: {
    '$store.getters.currentUser' (authUser) {
      if (authUser.type === USER_TYPE.SUPPLIER) {
        this.$store.dispatch('navigation/setUserMenuItems', [
          { title: 'My Profile', to: { name: 'myprofile' } }
        ])
      }
    }
  },
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    vm.$store.dispatch('page/setLoading', true)
    vm.$store.dispatch('currentUser/getCurrentUser')
      .then()
      .catch(error => console.error(error))
      .then(() => {
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

  /deep/ .f-userMenu__userLevel {
    background-color: var(--v-primary-base) !important;
  }

  /* Custom CSS for top nav bar menu - As a supplier*/
  /deep/ .title.secondary--text {
    color: var(--v-primary-base) !important;
  }
  /deep/ .v-avatar.accent {
    border: 4px solid var(--v-primary-base)!important;
  }
  /deep/ .ff-user-menu .v-list__tile__title {
    color: var(--v-primary-base) !important;
  }
  /deep/ .v-list__tile--active .v-list__tile__title {
    color: #fafafa !important;
  }
</style>
