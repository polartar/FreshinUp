<template>
  <v-container
    grid-list-xs
    fluid
    pa-2
  >
    <v-layout
      row
      wrap
      justify-space-between
      align-center
    >
      <v-flex>
        <v-layout
          row
          align-start
        >
          <v-flex
            style="max-width: 48px"
            mr-2
          >
            <v-avatar>
              <img
                v-if="admin.avatar"
                :src="admin.avatar"
              >
              <img
                v-else
                src="https://cdn.vuetifyjs.com/images/lists/2.jpg"
              >
            </v-avatar>
          </v-flex>
          <v-flex grow>
            <div class="caption font-weight-bold secondary--text">
              {{ admin.name }}
            </div>
            <v-chip
              class="ma-0 font-weight-bold text-uppercase small-caption"
              small
              color="secondary"
              dark
              depressed
            >
              {{ admin.level_name }}
            </v-chip>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex
        class="fresh-contact-item"
        fill-height
        :pl-2="hideDivider"
      >
        <v-layout
          row
          justify-start
          align-center
        >
          <v-divider
            v-if="isDividerVisible"
            vertical
            class="ml-1 mr-2 mt-0 mb-0"
          />
          <v-flex
            style="max-width: 48px"
            pt-2
          >
            <v-avatar
              color="secondary"
              size="28"
            >
              <v-icon class="white--text icon-caption">
                fa fa-envelope
              </v-icon>
            </v-avatar>
          </v-flex>
          <div class="font-weight-bold new-grey--text small-caption mt-2 ml-2">
            {{ admin.email }}
          </div>
        </v-layout>
      </v-flex>
      <v-flex
        class="fresh-contact-item"
        fill-height
        :pl-2="hideDivider"
      >
        <v-layout
          row
          justify-start
          align-center
        >
          <v-divider
            v-if="isDividerVisible"
            vertical
            class="ml-1 mr-2 mt-0 mb-0"
          />
          <v-flex
            style="max-width: 48px"
            pt-2
          >
            <v-avatar
              color="secondary"
              size="28"
            >
              <v-icon class="white--text icon-caption">
                fa fa-phone
              </v-icon>
            </v-avatar>
          </v-flex>
          <div class="text-uppercase new-grey--text small-caption font-weight-bold mt-2 ml-2">
            {{ admin.office_phone }}
          </div>
        </v-layout>
      </v-flex>
      <v-flex
        class="fresh-contact-item"
        fill-height
        :pl-2="hideDivider"
      >
        <v-layout
          row
          justify-start
          align-center
        >
          <v-divider
            v-if="isDividerVisible"
            vertical
            class="ml-1 mr-2 mt-0 mb-0"
          />
          <v-flex
            style="max-width: 48px"
            pt-2
          >
            <v-avatar
              color="secondary"
              size="28"
            >
              <v-icon class="white--text icon-caption">
                fa fa-mobile-alt
              </v-icon>
            </v-avatar>
          </v-flex>
          <div class="text-uppercase new-grey--text small-caption font-weight-bold mt-2 ml-2">
            {{ admin.mobile_phone }}
          </div>
        </v-layout>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  props: {
    admin: {
      type: Object,
      default: () => ({})
    },
    column: {
      type: Boolean,
      default: false
    },
    hideDivider: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    isDividerVisible () {
      return !this.hideDivider
    }
  }
}
</script>

<style scoped>
  .small-caption {
    font-size: 11px !important;
  }
  .icon-caption {
    font-size: 15px !important;
  }
  .fresh-contact-item {
    min-width: 200px;
  }
</style>
