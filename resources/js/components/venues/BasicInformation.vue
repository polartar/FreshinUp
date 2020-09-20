<template>
  <v-card>
    <v-card-title>
      Basic Information
      <v-progress-linear
        v-if="loading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-layout pa-3>
      <v-flex
        xs8
        pr-3
      >
        <v-flex
          xs12
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Venue name
          </div>
          <v-text-field
            v-model="name"
            placeholder="Name"
            single-line
            outline
          />
        </v-flex>
        <v-flex
          xs12
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Address line 1
          </div>
          <v-text-field
            v-model="address_line_1"
            placeholder="Address 1"
            single-line
            outline
          />
        </v-flex>
        <v-flex
          xs12
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Address line 2
          </div>
          <v-text-field
            v-model="address_line_2"
            placeholder="Address 2"
            single-line
            outline
          />
        </v-flex>
        <v-layout row>
          <v-flex xs8>
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Owned by
            </div>
            <v-flex
              xs12
              sm6
              md8
              align-center
              layout
            >
              <v-avatar
                :tile="false"
                :size="80"
                color="grey lighten-4"
              >
                <img
                  :src="owner.avatar"
                  alt="avatar"
                >
              </v-avatar>
              <div class="mx-2 px-2">
                <div class="primary--text subheading">
                  {{ owner.name }}
                </div>
                <div class="grey--text text--darken-2">
                  {{ owner.email }}
                </div>
                <div class="grey--text text--darken-2">
                  {{ owner.mobile_phone }}
                </div>
              </div>
            </v-flex>
          </v-flex>
          <v-flex
            xs4
            layout
            column
            align-content-center
            justify-center
          >
            <v-btn
              depressed
              disabled
              color="primary"
            >
              Change user
            </v-btn>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex
        xs4
        pl-3
      >
        MAP
      </v-flex>
    </v-layout>
    <v-divider />
    <v-card-actions class="pa-3 d-flex justify-space-between">
      <div class="d-flex">
        <div>
          <v-btn
            depressed
            disabled
            @click="onCancel"
          >
            Cancel
          </v-btn>
          <v-btn
            depressed
            disabled
            color="primary"
            @click="save"
          >
            Save Changes
          </v-btn>
        </div>
        <div class="text-xs-right">
          <v-btn
            depressed
            disabled
            @click="onDeleteVenue"
          >
            Delete Venue
          </v-btn>
        </div>
      </div>
    </v-card-actions>
  </v-card>
</template>
<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
import pick from 'lodash/pick'
import keys from 'lodash/keys'

export const DEFAULT_VENUE = {
  uuid: '',
  name: '',
  address_line_1: '',
  address_line_2: '',
  owner_uuid: '',
  owner: {
    name: '',
    email: '',
    mobile_phone: '',
    avatar: ''
  }
}

export default {
  mixins: [MapValueKeysToData],
  props: {
    loading: { type: Boolean, default: false }
  },
  data () {
    return {
      ...DEFAULT_VENUE
    }
  },
  methods: {
    onCancel () {
      this.$emit('cancel')
    },
    onDeleteVenue () {
      this.$emit('delete', pick(this, keys(this.value)))
    }
  }
}
</script>
