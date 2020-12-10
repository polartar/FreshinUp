<template>
  <v-card>
    <v-layout
      row
      class="pa-3"
    >
      <v-flex shrink>
        <f-user-avatar
          :user="user"
          :size="64"
        />
      </v-flex>
      <v-flex class="pl-3">
        <h4 class="title primary--text">
          {{ user.name }}
        </h4>
        <v-chip
          v-if="!hideLevel"
          class="mx-0 pl-3 pr-3 f-userMenu__userLevel"
          small
          color="primary"
          disabled
          text-color="white"
        >
          {{ user.level_name }}
        </v-chip>
        <div>
          <template v-if="userField === 'title,company_name'">
            <span v-if="user.title">
              {{ user.title }} @
            </span>
            {{ user.company_name }}
          </template>
          <template v-else>
            {{ user[userField] }}
          </template>
        </div>
      </v-flex>
    </v-layout>
    <v-divider />
    <v-list class="pb-0">
      <v-list-tile
        v-for="(item, index) in menuItems"
        :key="index"
        :to="item.to"
        class="grey--text"
        active-class="primary--text"
      >
        <v-list-tile-content>
          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-divider />
      <v-list-tile
        @click="$emit('signout')"
      >
        <v-list-tile-title
          class="grey--text"
        >
          Sign Out
        </v-list-tile-title>
      </v-list-tile>
    </v-list>
  </v-card>
</template>

<script>
import FUserAvatar from '@freshinup/core-ui/src/components/FUserAvatar'
import FUserMenu from '@freshinup/core-ui/src/components/FUserMenu'
export default {
  components: {
    FUserAvatar
  },
  extends: FUserMenu
}
</script>
