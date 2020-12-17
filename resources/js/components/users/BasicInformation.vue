<template>
  <v-card>
    <v-card-title>
      <h3 class="grey--text">
        Basic Information
      </h3>
      <v-progress-linear
        v-if="loading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-layout
      row
      wrap
      class="pa-3"
    >
      <v-flex xs8>
        <v-layout
          row
          wrap
        >
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              First name
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Last name
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Company
            </div>
            <v-text-field
              v-if="modeDetail"
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
            <div
              v-else
              class="pt-4"
            >
              Company name
            </div>
          </v-flex>
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Title
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
        </v-layout>
        <v-layout
          v-if="modeDetail"
          row
          wrap
        >
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              User type
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Role
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Food fleet manager
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
        </v-layout>
        <v-layout
          row
          wrap
        >
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Email
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
        </v-layout>
        <v-layout
          row
          wrap
        >
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Office phone
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
          <v-flex
            xs6
            px-2
          >
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Mobile phone
            </div>
            <v-text-field
              v-model="name"
              placeholder="Name"
              single-line
              outline
            />
          </v-flex>
        </v-layout>
        <v-layout
          v-if="!modeDetail"
          row
          wrap
        >
          <v-flex
            xs3
            px-1
          >
            <v-btn
              :loading="loading"
              depressed
              color="primary"
              @click="save"
            >
              Change password
            </v-btn>
          </v-flex>
        </v-layout>
        <v-flex
          v-if="modeDetail"
          xs12
          px-1
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
        xs4
        pl-4
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
    <v-divider />
    <v-layout
      pa-3
    >
      <v-flex>
        <v-btn
          :loading="loading"
          depressed
          @click="save"
        >
          Cancel
        </v-btn>
        <v-btn
          :loading="loading"
          depressed
          color="primary"
          @click="save"
        >
          Save changes
        </v-btn>
      </v-flex>
      <v-flex class="text-xs-right">
        <v-btn
          :loading="loading"
          depressed
          @click="save"
        >
          Delete account
        </v-btn>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
import get from 'lodash/get'

import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_STORE = {
  image: null
}

export const DEFAULT_IMAGE = 'https://via.placeholder.com/800x600.png'

export default {
  mixins: [MapValueKeysToData],
  props: {
    loading: { type: Boolean, default: false },
    modeDetail: { type: Boolean, default: false },
    // Overriding value prop from mixin MapValueKeysToData to grab the default values
    value: { type: Object, default: () => DEFAULT_STORE }
  },
  data () {
    return {
      ...DEFAULT_STORE
    }
  },
  computed: {
    editing () {
      return !!get(this.value, 'uuid')
    },
    hasImage () {
      return !!this.image && this.image !== DEFAULT_IMAGE
    },
    storeImage () {
      return this.hasImage ? this.image : '/images/default.png'
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
        this.image = event.target.result
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
      this.image = null
    }
  }
}
</script>
<style lang="scss" scoped>
.ff-profile-picture__image_input {
    display: none;
  }
</style>
