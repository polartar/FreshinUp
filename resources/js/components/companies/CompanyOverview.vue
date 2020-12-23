<template>
  <v-card>
    <v-card-title class="justify-space-between px-4 py-1">
      <span class="grey--text subheading font-weight-bold">Company overview</span>
      <v-btn
        depressed
        color="primary"
        @click="viewDetails"
      >
        View Details
      </v-btn>
    </v-card-title>
    <v-divider />
    <v-card-text>
      <v-progress-linear
        v-if="isLoading"
        indeterminate
      />
      <v-layout
        v-if="emptyCompany"
        class="pa-4"
        align-center
        justify-center
      >
        Empty company
      </v-layout>
      <v-layout
        v-else
        class="pa-4"
        align-center
        justify-center
      >
        <v-flex class="mr-4">
          <v-img
            :src="companyLogo"
            class="grey lighten-2"
          />
        </v-flex>
        <v-flex>
          <div class="primary--text subheading">
            {{ name }}
          </div>
          <div class="grey--text caption">
            Type: {{ typeName }}
          </div>
          <div class="grey--text caption">
            Status {{ statusName }}
          </div>
        </v-flex>
        <v-flex class="grey--text caption">
          Member {{ members_count }}
        </v-flex>
        <v-flex class="grey--text caption">
          {{ admin.level_name }}
        </v-flex>
        <v-flex>
          <v-layout align-center>
            <v-flex
              xs3
              mx-2
            >
              <f-user-avatar
                :tile="false"
                :user="admin.avatar"
                class="ff-venue-details__owner"
                :size="80"
              />
            </v-flex>
            <v-flex>
              <div class="primary--text subheading">
                {{ admin.name }}
              </div>
              <div class="grey--text caption">
                {{ admin.email }}
              </div>
              <div class="grey--text caption">
                {{ admin.level_name }} @{{ admin.company_name }}
              </div>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-card-text>
  </v-card>
</template>
<script>
import FUserAvatar from '@freshinup/core-ui/src/components/FUserAvatar'

import MapValueKeysToData from '~/mixins/MapValueKeysToData'

export const DEFAULT_COMPANY = {
  type: 0,
  status: 0,
  name: '',
  logo: '',
  members_count: 0,
  admin: {
    name: '',
    email: '',
    company_name: '',
    avatar: '',
    level_name: ''
  }
}

export const DEFAULT_IMAGE = 'https://via.placeholder.com/800x600.png'

export default {
  components: {
    FUserAvatar
  },
  mixins: [MapValueKeysToData],
  props: {
    // overriding value prop to define default value
    value: { type: Object, default: () =>  DEFAULT_COMPANY },
    isLoading: { type: Boolean, default: false },
    types: { type: Array, default: () => [] }
  },
  data () {
    return {
      ...DEFAULT_COMPANY
    }
  },
  computed: {
    companyLogo () {
      return this.logo || DEFAULT_IMAGE
    },
    typeName () {
      return 'Supplier'
    },
    statusName () {
      return 'Active'
    },
    emptyCompany () {
      return !this.name
    }
  },
  methods: {
    viewDetails () {
      this.$emit('manage-view', this.payload)
      this.$emit('manage', 'view', this.payload)
    }
  }
}
</script>
