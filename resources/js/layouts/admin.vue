<template>
  <div v-if="currentUser">
    <slot name="navigation-drawer">
      <v-navigation-drawer
        v-model="isDrawerOpen"
        :clipped="isClipped"
        :mini-variant="typeof isMini === 'boolean' ? isMini : false"
        disable-resize-watcher
        stateless
        app
        :dark="typeof isDark === 'boolean' ? isDark : true"
        :color="typeof isDark === 'boolean' ? '' : 'white'"
        width="220px"
      >
        <div class="logo-container">
          <img :src="logo">
        </div>
        <navigation-drawer-list :items="items" />
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
            Food Fleet Admins
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
            <v-avatar size="48">
              <img
                :src="currentUser.avatar"
                :alt="currentUser.name"
              >
            </v-avatar>
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
    <v-content>
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
import { mapState, mapGetters, mapActions } from 'vuex'
import { createHelpers } from 'vuex-map-fields'
import UserMenu from 'fresh-bus/components/layout/UserMenu.vue'
import NavigationDrawerList from 'fresh-bus/components/layout/NavigationDrawerList.vue'
import NotificationMenu from 'fresh-bus/components/layout/NotificationMenu.vue'
import FreshBusFooter from 'fresh-bus/components/Footer.vue'

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
    FreshBusFooter
  },
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
    ...mapState('navigationAdmin', [
      'items',
      'logo',
      'isDark',
      'isMini'
    ]),
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
    ...mapGetters('page', ['isLoading'])
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

  /deep/ .v-navigation-drawer .v-list__tile__action {
    display: none !important;
  }
  /deep/ .v-navigation-drawer .v-list .v-list__tile {
    padding: 0 24px !important;
  }
  /deep/ .v-navigation-drawer .v-list .v-list__tile .v-list__tile__title {
    color: var(--v-primary-base);
    font-weight: bold;
    text-transform: uppercase;
  }
  /deep/ .v-navigation-drawer .v-list .v-list__tile.v-list__tile--active {
    background-color: #000;
  }

  /deep/ .v-toolbar__content {
    padding-left: 10px;
  }
</style>
