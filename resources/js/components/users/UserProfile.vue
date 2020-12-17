<template>
  <v-layout
    row
    wrap
  >
    <v-flex
      xs12
      mb-4
    >
      <basic-information />
    </v-flex>
    <v-flex
      xs12
      mb-4
    >
      <notification-settings />
    </v-flex>
  </v-layout>
</template>

<script>

import NotificationSettings from './NotificationSettings.vue'
import BasicInformation from './BasicInformation.vue'
export default {
  components: {
    NotificationSettings,
    BasicInformation
  },
  props: {
    isCurrentUserAdmin: {
      type: Boolean,
      default: false
    },
    isCurrentUser: {
      type: Boolean,
      default: false
    },
    adminUser: {
      type: Object,
      default: () => ({})
    },
    user: {
      type: Object,
      default: () => ({})
    },
    company: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    totalCompanyMembers () {
      return this.company.members ? this.company.members.length : 0
    }
  }
}
</script>

<style scoped>
  .v-card {
    border-radius: 6px;
  }
</style>
