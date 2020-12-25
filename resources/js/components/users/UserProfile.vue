<template>
  <v-layout
    align-start
    row
    wrap
  >
    <v-flex
      v-if="company"
      xs12
      sm12
      md4
      lg3
      xl3
      mb-4
    >
      <profile-card
        :user="user"
        :company="company"
        :is-current-user="isCurrentUser"
        :is-current-user-admin="isCurrentUserAdmin"
      />
    </v-flex>
    <v-flex
      xs12
      sm12
      md8
      lg9
      xl9
      :class="{'pl-3': $vuetify.breakpoint.mdAndUp}"
    >
      <v-layout
        row
        wrap
      >
        <v-flex
          v-if="company"
          xs12
          mb-4
          px-0
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
        <v-flex
          xs12
          mb-4
          pa-0
        >
          <teams-list
            :teams="teams"
            :max-user-chips="$vuetify.breakpoint.md ? 2 : undefined"
            :title="isCurrentUser ? 'Your Teams' : 'Teams'"
          />
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import get from 'lodash/get'
import UserProfile from 'fresh-bus/components/users/UserProfile'
export default {
  extends: UserProfile,
  computed: {
    totalCompanyMembers () {
      return get(this.company, 'members.length', 0)
    }
  }
}
</script>
