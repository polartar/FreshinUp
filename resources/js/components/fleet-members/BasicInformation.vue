<template>
  <v-card>
    <v-card-title>
      Basic Information
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
              v-model="memberData.name"
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
              v-model="memberData.type"
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
            <v-text-field
              v-model="tagText"
              placeholder="Type a tag and hit enter (autocomplete)"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            v-if="memberData.tags.length"
            xs12
            pb-4
          >
            <v-chip
              v-for="(tag, index) of memberData.tags"
              :key="index"
              close
              color="orange"
            >
              {{ tag }}
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
              v-model="memberData.pos_system"
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
              class="mb-2 text-uppercase grey--text font-weight-bold d-flex justify-space-between position-relative"
              style="position: relative;"
            >
              <span>Business name</span>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    style="position: absolute; right: 0;"
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
              v-model="memberData.business_name"
              :items="[]"
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
              v-model="memberData.size_of_truck_trailer"
              placeholder="input value"
              single-line
              outline
            />
          </v-flex>
          <v-flex xs12>
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Owned by
            </div>
            <v-text-field
              v-model="memberData.owner"
              label="Name"
              single-line
              outline
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
              v-model="memberData.phone"
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
              v-model="memberData.state_of_incorporation"
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
              v-model="memberData.website"
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
              v-model="memberData.facebook"
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
              v-model="memberData.twitter"
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
              v-model="memberData.instagram"
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
              v-model="memberData.staff_notes"
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
        <div>
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
          >
            Upload Image
          </v-btn>
          <v-btn depressed>
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

export default {
  props: {
    member: {
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
    }
  },

  data () {
    let edit = get(this.member, 'uuid') !== null
    return {
      memberData: {
        name: edit ? get(this.member, 'name') : '',
        type: edit ? get(this.member, 'type_id') : null,
        tags: edit ? get(this.member, 'tags', []) : [],
        pos_system: edit ? get(this.member, 'pos_system') : '',
        business_name: edit ? get(this.member, 'business_name') : '',
        size_of_truck_trailer: edit ? get(this.member, 'size_of_truck_trailer') : '',
        owner: edit ? get(this.member, 'owner') : '',
        phone: edit ? get(this.member, 'phone') : '',
        state_of_incorporation: edit ? get(this.member, 'state_of_incorporation') : '',
        website: edit ? get(this.member, 'website') : '',
        twitter: edit ? get(this.member, 'twitter') : '',
        facebook: edit ? get(this.member, 'facebook') : '',
        instagram: edit ? get(this.member, 'instagram') : '',
        staff_notes: edit ? get(this.member, 'staff_notes') : ''
      },
      edit: edit,
      tagText: ''
    }
  },

  methods: {
    onCancel () {
      this.$router.push('/admin/fleet-members')
    },

    onSaveChanges () {
      this.$emit('save', this.memberData)
    },

    onDeleteMember () {
      this.$emit('delete', this.memberData)
    }
  }
}
</script>
