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
        wrap
      >
        <v-flex xs12 sm10 md8>
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
                placeholder="Name"
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
                placeholder="Name"
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
              <v-text-field
                v-if="forCustomer"
                :value="get(company, 'name')"
                placeholder="Name"
                single-line
                outline
              />
              <div
                v-else
                class="grey--text pt-4"
              >
                {{ get(company, 'name') }}
              </div>
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
                placeholder="Name"
                single-line
                outline
              />
            </v-flex>
          </v-layout>
          <v-flex
            v-if="forCustomer"
            xs6
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              User type
            </div>
            <v-select
              :items="types"
              v-model="type"
              item-text="name"
              item-value="id"
              placeholder="Type"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            v-if="forCustomer"
            xs6
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Role
            </div>
            <v-select
              :items="levels"
              v-model="level"
              item-text="name"
              item-value="id"
              placeholder="Level"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            v-if="forCustomer"
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
              placeholder="Manager"
              height="48"
              not-clearable
              flat
              @input="selectManager"
            />
          </v-flex>
          <v-flex
            xs12
            sm6
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
            xs3
          >
            <v-btn
              :loading="isLoading"
              depressed
              color="primary"
              @click="onChangePassword"
            >
              Change password
            </v-btn>
          </v-flex>
          <v-flex
            v-if="forCustomer"
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
        </v-flex>
        <v-flex
          xs12
          sm2
          md4
        >
          <div class="px-2 xs-12 md-4">
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
          </div>
          <div class="py-3 d-flex justify-space-between">
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
          </div>
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
import { USER_TYPE } from '~/store/modules/userTypes'

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
  mixins: [MapValueKeysToData],
  components: {
    Simple
  },
  props: {
    isLoading: { type: Boolean, default: false },
    // Overriding value prop from mixin MapValueKeysToData to grab the default values
    value: { type: Object, default: () => DEFAULT_USER },
    levels: { type: Array, default: () => [] },
    types: { type: Array, default: () => [] },
  },
  data () {
    return {
      ...DEFAULT_USER
    }
  },
  computed: {
    forCustomer () {
      return this.type === USER_TYPE.CUSTOMER
    },
    editing () {
      return !!get(this.value, 'uuid')
    },
    hasImage () {
      return !!this.avatar && this.avatar !== DEFAULT_IMAGE
    },
    storeImage () {
      return this.hasImage ? this.avatar : '/images/default.png'
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
    }
  }
}
</script>
<style lang="scss" scoped>
.ff-profile-picture__image_input {
    display: none;
  }
</style>
