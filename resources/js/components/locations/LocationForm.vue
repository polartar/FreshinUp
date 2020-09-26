<template>
  <v-layout
    column
    class="grey--text"
  >
    <v-flex>
      <v-layout row>
        <v-flex xs12 sm6>
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Category
          </div>
          <v-select
            v-model="category_id"
            :items="categories"
            item-text="name"
            item-value="id"
            single-line
            outline
          />
        </v-flex>
      </v-layout>
    </v-flex>
    <v-flex
      v-if="!isIndoor"
      text-xs-center
    >
      MAP coming soon
      <div style="background-color: #e5e5e5; width: 100%; height: 200px;" />
    </v-flex>
    <v-flex class="justify-content-center" v-if="isIndoor">
      <v-layout class="justify-content-between" column>
        <v-flex v-for="(file, fileIndex) in files" :key="fileIndex">
          <span class="text-uppercase">{{ file.name }}</span>
          <span>
          {{ file.size }}
          <v-icon
            right
          >
            fas fa-upload
          </v-icon>
        </span>
        </v-flex>
      </v-layout>
      <v-btn
        class="white--text"
        depressed
        color="grey"
      >
        <v-icon
          dark
          left
        >
          fas fa-upload
        </v-icon>
        upload Floor Image / Attachment
      </v-btn>
    </v-flex>
    <v-layout>
      <v-flex pr-2>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Location
        </div>
        <v-text-field
          v-model="name"
          single-line
          outline
        />
      </v-flex>
      <v-flex pl-2>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Capacity
        </div>
        <v-text-field
          v-model="capacity"
          single-line
          outline
        />
      </v-flex>
    </v-layout>
    <v-flex>
      <div class="mb-2 text-uppercase grey--text font-weight-bold">
        Description
      </div>
      <v-textarea
        v-model="details"
        single-line
        outline
      />
    </v-flex>
    <v-flex class="py-3 d-flex justify-space-between">
      <v-btn
        depressed
        @click="onCancel"
      >
        Cancel
      </v-btn>
      <v-btn
        depressed
        color="primary"
        @click="save"
      >
        Save changes
      </v-btn>
    </v-flex>
  </v-layout>
</template>

<script>
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

export const DEFAULT_LOCATION = {
  name: '',
  venue_uuid: '',
  spots: '',
  capacity: '',
  details: '',
  category_id: '',
  files: []
}

/**
   * These comments are just for intellisense (IDE)
   * @property {string} name
   * @property {string} venue_uuid
   * @property {number} spots
   * @property {number} capacity
   * @property {number} category_id
   * @property {string} details
   * @property {Object[]} files
   */
export default {
  mixins: [MapValueKeysToData],
  props: {
    categories: { type: Array, default: () => [] }
  },
  data () {
    return {
      ...DEFAULT_LOCATION
    }
  },
  computed: {
    isIndoor () {
      return this.category_id === 1
    }
  },
  methods: {
    onCancel () {
      this.$emit('cancel')
    }
  }
}
</script>
