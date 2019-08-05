<template>
  <v-layout
    align-start
    row
    wrap
  >
    <v-flex
      xs12
      sm12
      md4
      mb-4
      px-2
    >
      <profile-card
        :user="user"
        :company="company"
        :is-current-user="isCurrentUser"
        :is-current-user-admin="isCurrentUserAdmin"
        class="px-4"
      />
    </v-flex>
    <v-flex
      xs12
      sm12
      md8
      :class="{'pl-3': $vuetify.breakpoint.mdAndUp}"
    >
      <v-layout
        row
        wrap
      >
        <v-flex
          xs12
          mb-4
          px-2
        >
          <company-info
            :user="user"
            :admin-user="adminUser"
            :is-current-user-admin="isCurrentUserAdmin"
            :total-members="totalCompanyMembers"
            :company="company"
            :title="isCurrentUser ? 'Your Company' : 'Company'"
          />
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import ProfileCard from 'fresh-bus/components/users/ProfileCard.vue'
import CompanyInfo from 'fresh-bus/components/companies/CompanySummary.vue'
export default {
  components: {
    ProfileCard,
    CompanyInfo
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
