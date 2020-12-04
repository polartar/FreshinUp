<template>
  <v-card>
    <v-card-title>
      <h3>Basic Information</h3>
      <v-progress-linear
        v-if="isLoading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-layout pa-3>
      <v-flex
        xs8
        lg7
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
        <v-flex xs12>
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Address line 1
          </div>
          <v-autocomplete
            :value="address_line_1"
            :items="addresses"
            :loading="addressesLoading"
            hide-no-data
            hide-selected
            item-text="text"
            placeholder="Start typing to Search"
            return-object
            single-line
            outline
            @input="onPlaceInput"
            @update:searchInput="searchPlaces"
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
              v-if="hasOwner"
              xs12
              sm6
              md8
              align-center
              layout
            >
              <f-user-avatar
                :tile="false"
                :user="owner"
                class="ff-venue-details__owner"
                :size="80"
              />
              <div class="mx-2 px-2">
                <div class="primary--text subheading">
                  {{ get(owner, 'name') }}
                </div>
                <div class="grey--text text--darken-2">
                  {{ get(owner, 'email') }}
                </div>
                <div class="grey--text text--darken-2">
                  {{ get(owner, 'mobile_phone') }}
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
            <v-dialog
              v-model="changeUserDialog"
              max-width="500"
            >
              <template v-slot:activator="{ on }">
                <v-btn
                  depressed
                  color="primary"
                  @click="changeUserDialog = true"
                >
                  Change user
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <v-layout
                    row
                    space-between
                    align-center
                  >
                    <v-flex>
                      <h4>Change user</h4>
                    </v-flex>
                    <v-btn
                      small
                      round
                      color="grey"
                      class="white--text"
                      @click="changeUserDialog = false"
                    >
                      <v-flex>
                        <v-icon
                          small
                          class="white--text"
                        >
                          fa fa-times
                        </v-icon>
                      </v-flex>
                      <v-flex>
                        Close
                      </v-flex>
                    </v-btn>
                  </v-layout>
                </v-card-title>
                <v-divider />
                <v-card-text>
                  Owner
                  <simple
                    url="users"
                    term-param="term"
                    results-id-key="uuid"
                    :value="owner.uuid"
                    placeholder="Search / Select owner"
                    background-color="white"
                    class="mt-0 pt-0"
                    height="48"
                    not-clearable
                    solo
                    flat
                    @input="selectOwner"
                  />
                </v-card-text>
              </v-card>
            </v-dialog>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex
        xs4
        lg5
        pl-3
      >
        <div
          style="height: 100%;"
          class="my-2"
        >
          <f-map
            v-if="mapboxAccessToken"
            :access-token="mapboxAccessToken"
            :center="mapCenter"
            :max-bounds="borderBox"
          >
            <f-map-marker
              v-if="mapCenter"
              :coordinates="mapCenter"
              :color="markerColor"
            />
          </f-map>
        </div>
      </v-flex>
    </v-layout>
    <v-divider />
    <v-card-actions class="pa-3 d-flex justify-space-between">
      <div class="d-flex">
        <div>
          <v-btn
            depressed
            @click="onCancel"
          >
            Cancel
          </v-btn>
          <v-btn
            depressed
            color="primary"
            :loading="isLoading"
            @click="save"
          >
            {{ isEditing ? 'Save Changes' : 'Submit' }}
          </v-btn>
        </div>
        <div class="text-xs-right">
          <v-btn
            depressed
            :loading="isLoading"
            :disabled="!isEditing"
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
import get from 'lodash/get'
import theme from '~/theme'
import Simple from 'fresh-bus/components/search/simple'
import FUserAvatar from '@freshinup/core-ui/src/components/FUserAvatar'
import FMap from '~/components/FMap'
import FMapMarker from '~/components/FMapMarker'
import debounce from 'lodash/debounce'

export const DEFAULT_VENUE = {
  uuid: '',
  name: '',
  address_line_1: '',
  address_line_2: '',
  latitude: '',
  longitude: '',
  owner_uuid: '',
  owner: {
    uuid: '',
    name: '',
    email: '',
    mobile_phone: '',
    avatar: ''
  }
}
export default {
  components: {
    Simple,
    FMap,
    FMapMarker,
    FUserAvatar
  },
  mixins: [MapValueKeysToData],
  props: {
    isLoading: { type: Boolean, default: false },
    addressesLoading: { type: Boolean, default: false },
    addresses: { type: Array, default: () => [] },
    mapboxAccessToken: { type: String, default: null }
  },
  data () {
    return {
      ...DEFAULT_VENUE,
      markerColor: theme.primary,
      changeUserDialog: false,
      borderBox: null
    }
  },
  computed: {
    hasOwner () {
      return Boolean(get(this.owner, 'uuid'))
    },
    isEditing () {
      return Boolean(this.uuid)
    },
    mapCenter () {
      if (!this.latitude || !this.longitude) {
        return undefined
      }
      return [this.longitude, this.latitude]
    }
  },
  methods: {
    get,
    selectOwner (user) {
      this.owner = Object.assign({}, this.owner, user)
      this.owner_uuid = user.uuid
    },
    onCancel () {
      this.$emit('cancel')
    },
    onDeleteVenue () {
      this.$emit('delete', this.payload)
    },
    searchPlaces: debounce(function (query) {
      if (query && query === this.address_line_1) {
        this.$emit('search-places', query)
      }
    }, 400),
    onPlaceInput (place) {
      // lodash.get method is here for clarity purpose
      this.borderBox = get(place, 'bbox')
      this.address_line_1 = get(place, 'text', '')
      this.address_line_2 = get(place, 'place_name', '')
        .replace(`${this.address_line_1}, `, '')
      const [longitude, latitude] = get(place, 'center', [])
      this.latitude = latitude
      this.longitude = longitude
    }
  }
}
</script>

<style scoped>
  .ff-venue-details__owner span {
    border-radius: 50%;
  }
</style>
