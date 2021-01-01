<template>
  <v-card>
    <v-card-title>
      <h3 class="grey--text">
        Basic Information
      </h3>
      <v-progress-linear
        v-if="isLoading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-card-text>
      <v-layout
        row
        data-wrap
      >
        <v-flex
          xs12
          sm10
          lg8
        >
          <v-layout
            row
            wrap
          >
            <v-flex
              xs12
              sm6
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                First name
              </div>
              <v-text-field
                v-model="first_name"
                placeholder="First Name"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              xs12
              sm6
              class="pl-2"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Last name
              </div>
              <v-text-field
                v-model="last_name"
                placeholder="Last Name"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              xs6
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Company
              </div>
              <simple
                class="mb-2"
                single-line
                outline
                url="companies"
                term-param="filter[name]"
                results-id-key="uuid"
                :value="company_id + ''"
                :disabled="!isAdmin"
                placeholder="Search / Select Company"
                height="48"
                not-clearable
                solo
                flat
                @input="selectCompany"
              />
            </v-flex>
            <v-flex
              xs6
              class="pl-2"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Title
              </div>
              <v-text-field
                v-model="title"
                placeholder="Title"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              v-if="isAdmin"
              xs6
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                User type
              </div>
              <v-select
                v-model="type"
                :items="types"
                item-text="name"
                item-value="id"
                placeholder="Type"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              v-if="isAdmin"
              xs6
              class="pl-2"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Role
              </div>
              <v-select
                v-model="level"
                :items="levels"
                item-text="name"
                item-value="id"
                placeholder="Level"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              v-if="isAdmin"
              xs6
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Food fleet manager
              </div>
              <simple
                class="mb-2"
                single-line
                outline
                url="users"
                term-param="term"
                results-id-key="uuid"
                :value="manager_uuid"
                placeholder="Search / select user"
                height="48"
                not-clearable
                flat
                @input="selectManager"
              />
            </v-flex>
            <v-flex
              :class="{'pl-2': isAdmin, 'sm-6': isAdmin, 'xs-12': !isAdmin}"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Email
              </div>
              <v-text-field
                v-model="email"
                placeholder="Email"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              xs12
              sm6
              :class="{'pl-2': !isAdmin}"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Office phone
              </div>
              <v-text-field
                v-model="office_phone"
                placeholder="Office phone"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              xs12
              sm6
              :class="{'pl-2': isAdmin}"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Mobile phone
              </div>
              <v-text-field
                v-model="mobile_phone"
                placeholder="Mobile phone"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              v-if="isAdmin"
              xs12
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Notes / Additional Info
              </div>
              <v-textarea
                v-model="notes"
                single-line
                outline
                data-vv-name="notes"
                label="Notes / Additional Info"
              />
            </v-flex>
            <v-flex
              xs12
            >
              <v-btn
                :loading="isLoading"
                depressed
                color="primary"
                disabled
                @click="onChangePassword"
              >
                Change password
              </v-btn>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex
          xs12
          sm2
          lg4
        >
          <v-layout
            row
            wrap
          >
            <v-flex
              xs12
              class="px-2"
            >
              <div class="mb-2 text-uppercase grey--text font-weight-bold">
                Profile picture
              </div>
              <input
                class="ff-profile-picture__image_input"
                type="file"
                @change="storeImageChange"
              >
              <v-img
                :src="storeImage"
                class="grey lighten-2"
              />
            </v-flex>
            <v-flex class="py-3 d-flex justify-space-between">
              <v-btn
                depressed
                color="primary"
                @click="updateStoreImage"
              >
                Upload Image
              </v-btn>
              <v-btn
                depressed
                :disabled="!hasImage"
                @click="deleteStoreImage"
              >
                Delete Image
              </v-btn>
            </v-flex>
            <v-flex
                xs12
                pl-2
                v-if="isAdmin"
              >
                <div class="mb-2 text-uppercase grey--text font-weight-bold">
                  Status
                </div>
                <v-select
                  class="pt-0"
                  v-model="status"
                  :items="statuses"
                  placeholder="Status"
                  data-vv-name="status"
                  item-value="id"
                  item-text="name"
                  outline
                />
              </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-card-text>
    <v-divider />
    <v-layout
      pa-3
    >
      <v-flex>
        <v-btn
          :loading="isLoading"
          depressed
          disabled
          @click="onCancel"
        >
          Cancel
        </v-btn>
        <v-btn
          :loading="isLoading"
          depressed
          color="primary"
          @click="save"
        >
          Save changes
        </v-btn>
      </v-flex>
      <v-flex class="text-xs-right">
        <v-btn
          :loading="isLoading"
          :disabled="!isEditing"
          depressed
          @click="onDelete"
        >
          Delete account
        </v-btn>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
import get from 'lodash/get'
import Simple from 'fresh-bus/components/search/simple'

import MapValueKeysToData from '~/mixins/MapValueKeysToData'

export const DEFAULT_USER = {
  id: null,
  uuid: '',
  company_id: null,
  status: null,
  type: null,
  level: null,
  first_name: '',
  last_name: '',
  name: '',
  email: '',
  mobile_phone: '',
  office_phone: '',
  notes: null,
  title: null,
  avatar: '',
  requested_company: null,
  company: null,
  last_login: '',
  has_admin_access: false,
  joined_at: '',
  manager_uuid: ''
}

export const DEFAULT_IMAGE = 'https://via.placeholder.com/800x600.png'

export default {
  components: {
    Simple
  },
  mixins: [MapValueKeysToData],
  props: {
    isLoading: { type: Boolean, default: false },
    isAdmin: { type: Boolean, default: false },
    // Overriding value prop from mixin MapValueKeysToData to grab the default values
    value: { type: Object, default: () => DEFAULT_USER },
    levels: { type: Array, default: () => [] },
    types: { type: Array, default: () => [] },
    statuses: { type: Array, default: () => [] }
  },
  data () {
    return {
      ...DEFAULT_USER
    }
  },
  computed: {
    isEditing () {
      return !!get(this.value, 'uuid')
    },
    hasImage () {
      return !!this.avatar && this.avatar !== DEFAULT_IMAGE
    },
    storeImage () {
      return this.hasImage ? this.avatar : DEFAULT_IMAGE
    }
  },
  methods: {
    get,
    storeImageChange (e) {
      const file = e.target.files[0]
      if (!file) {
        return false
      }
      const reader = new FileReader()
      reader.onload = (event) => {
        this.avatar = event.target.result
      }
      reader.readAsDataURL(file)
    },
    updateStoreImage () {
      const image = this.$el.querySelector('.ff-profile-picture__image_input')
      if (!image) {
        return false
      }
      image.click()
    },
    deleteStoreImage () {
      const image = this.$el.querySelector('.ff-profile-picture__image_input')
      if (!image) {
        return false
      }
      image.value = null
      this.avatar = null
    },
    onCancel () {
      this.$emit('cancel')
    },
    onDelete () {
      this.$emit('delete', this.payload)
    },
    onChangePassword () {
      this.$emit('change-password', this.payload)
    },
    selectManager (user) {
      this.manager_uuid = user ? user.uuid : null
    },
    selectCompany (company) {
      this.company_id = company ? company.id : null
    }
  }
}
</script>
<style  scoped>
  .ff-profile-picture__image_input {
    display: none;
  }

  >>> .v-select__selections {
    padding-top: 0!important;
  }
</style>
