<template>
  <v-card>
    <v-card-title>
      Basic Information
      <v-progress-linear indeterminate v-if="loading"/>
    </v-card-title>
    <v-divider />
    <v-layout class="pa-3">
      <v-flex
        xs8
        pr-3
      >
        <v-layout
          row
          wrap
        >
          <v-flex
            xs9
            pr-4
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Fleet member name
            </div>
            <v-text-field
              v-model="storeData.name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
          <v-flex xs3>
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Type
            </div>
            <v-select
              v-model="storeData.type_id"
              :items="types"
              item-text="name"
              item-value="id"
              single-line
              outline
            />
          </v-flex>
          <v-flex xs12>
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Tags
            </div>
            <f-autocomplete
              ref="tags"
              no-filter
              placeholder="Type a tag"
              value-fetch
              item-value="uuid"
              item-text="name"
              url="/foodfleet/store-tags"
              hide-details
              class="mb-4"
              solo
              outline
              flat
              not-clearable
              @input="onTagSelected"
            />
          </v-flex>
          <v-flex
            v-if="storeData.tags.length"
            xs12
            pb-4
          >
            <v-chip
              v-for="(tag, index) of storeData.tags"
              :key="index"
              close
              color="orange"
              @click="deleteTag(tag)"
            >
              {{ tag.name }}
            </v-chip>
          </v-flex>
          <v-flex
            xs4
            pr-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Pos system
            </div>
            <v-select
              v-model="storeData.pos_system"
              :items="locations"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs4
            pr-2
            pl-2
          >
            <div
              class="ff-fleet-members__basic_information mb-2 text-uppercase grey--text font-weight-bold d-flex justify-space-between position-relative"
            >
              <span>Business name</span>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="ff-fleet-members__tooltip-icon"
                    small
                    v-bind="attrs"
                    v-on="on"
                  >
                    info
                  </v-icon>
                </template>
                This is the business name you entered for your fleet member on square
              </v-tooltip>
            </div>
            <v-select
              v-model="storeData.square_id"
              :items="squareLocations"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs4
            pl-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Size of the truck / trailer
            </div>
            <v-text-field
              v-model="storeData.size_of_truck_trailer"
              placeholder="input value"
              single-line
              outline
            />
          </v-flex>
          <v-flex xs12 class="mb-2">
            <div class="text-uppercase grey--text font-weight-bold">
              Owned by
            </div>
            <simple
              label="Name"
              single-line
              outline
              url="users?filter[type]=1"
              term-param="term"
              results-id-key="uuid"
              :value="storeData.owner_uuid"
              placeholder="Name"
              height="48"
              not-clearable
              flat
              @input="onOwnerSelected"
            />
          </v-flex>
          <v-flex
            xs6
            pr-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Contact phone
            </div>
            <v-text-field
              v-model="storeData.phone"
              placeholder="Phone"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            pl-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              State of incorporation
            </div>
            <v-text-field
              v-model="storeData.state_of_incorporation"
              placeholder="State"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            pr-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Website
            </div>
            <v-text-field
              v-model="storeData.website"
              placeholder="www.example.com"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            pl-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Facebook
            </div>
            <v-text-field
              v-model="storeData.facebook"
              placeholder="facebook account"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            pr-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Twitter
            </div>
            <v-text-field
              v-model="storeData.twitter"
              placeholder="Twitter account"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            pl-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Instagram
            </div>
            <v-text-field
              v-model="storeData.instagram"
              placeholder="Instagram account"
              single-line
              outline
            />
          </v-flex>
          <v-flex xs12>
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Food fleet staff notes
            </div>
            <v-textarea
              v-model="storeData.staff_notes"
              placeholder="Food fleet staff notes"
              single-line
              outline
            />
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex
        xs4
        pl-3
      >
        <div class="px-2">
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Truck / Trailer image
          </div>
          <v-img
            :src="`https://picsum.photos/510/300?random`"
            :lazy-src="`https://picsum.photos/10/6?image=15`"
            aspect-ratio="1.7"
            class="grey lighten-2"
          />
        </div>
        <div class="py-3 d-flex justify-space-between">
          <v-btn
            depressed
            color="primary"
            disabled
          >
            Upload Image
          </v-btn>
          <v-btn
            depressed
            disabled
          >
            Delete Image
          </v-btn>
        </div>
      </v-flex>
    </v-layout>
    <v-divider />
    <v-card-actions class="pa-3 d-flex justify-space-between">
      <div>
        <v-btn
          depressed
          @click="onCancel"
        >
          Cancel
        </v-btn>
        <v-btn
          :disabled="loading"
          depressed
          color="primary"
          @click="onSaveChanges"
        >
          Save changes
        </v-btn>
      </div>
      <div
        v-if="edit"
        style="text-align: right;"
      >
        <v-btn
          depressed
          disabled
          @click="onDeleteMember"
        >
          Delete Fleet Member
        </v-btn>
      </div>
    </v-card-actions>
  </v-card>
</template>
<script>
import { get } from 'lodash'
import FAutocomplete from '../../components/FAutocomplete'
import Simple from 'fresh-bus/components/search/simple'

export default {
  components: { FAutocomplete, Simple },
  props: {
    loading: { type: Boolean, default: false },
    store: {
      type: Object,
      default: null
    },
    types: {
      type: Array,
      default: () => []
    },
    locations: {
      type: Array,
      default: () => []
    },
    squareLocations: {
      type: Array,
      default: () => []
    }
  },
  data () {
    let edit = get(this.store, 'uuid') !== null
    return {
      storeData: {
        name: edit ? get(this.store, 'name') : '',
        type_id: edit ? get(this.store, 'type_id') : null,
        tags: edit ? get(this.store, 'tags', []) : [],
        pos_system: edit ? get(this.store, 'pos_system') : '',
        square_id: edit ? get(this.store, 'square_id') : '',
        size_of_truck_trailer: edit ? get(this.store, 'size_of_truck_trailer') : '',
        owner_uuid: edit ? get(this.store, 'owner_uuid') : '',
        phone: edit ? get(this.store, 'phone') : '',
        state_of_incorporation: edit ? get(this.store, 'state_of_incorporation') : '',
        website: edit ? get(this.store, 'website') : '',
        twitter: edit ? get(this.store, 'twitter') : '',
        facebook: edit ? get(this.store, 'facebook') : '',
        instagram: edit ? get(this.store, 'instagram') : '',
        staff_notes: edit ? get(this.store, 'staff_notes') : ''
      },
      edit: edit
    }
  },

  methods: {
    deleteTag (tag) {
      this.storeData.tags = this.storeData.tags.filter(t => t.uuid === tag.uuid)
    },
    onOwnerSelected (owner) {
      this.storeData.owner_uuid = owner ? owner.uuid : null
    },
    onTagSelected (tag) {
      // on add if not already
      if (this.storeData.tags.findIndex(t => t.uuid === tag.uuid) === -1) {
        this.storeData.tags.push(tag)
      }
      this.$refs.tags.clear()
    },
    onCancel () {
      this.$emit('cancel')
    },
    onSaveChanges () {
      this.$emit('save', this.storeData)
    },
    onDeleteMember () {
      this.$emit('delete', this.storeData)
    }
  }
}
</script>
<style lang="scss" scoped>
.ff-fleet-members__basic_information {
  position: relative;

  .ff-fleet-members__tooltip-icon {
    position: absolute;
    right: 0;
  }
}
</style>
