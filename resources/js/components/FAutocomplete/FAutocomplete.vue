<template>
  <v-autocomplete
    :items="internalItems"
    :loading="isLoading"
    :placeholder="placeholder"
    :item-text="itemText"
    :item-value="itemValue"
    :clearable="!notClearable"
    :value="value"
    hide-no-data
    :return-object="!returnValue"
    no-filter
    avatar-key="avatarKey"
    v-bind="$attrs"
    @input.native="termUpdate"
    v-on="$listeners"
    @click:clear="onClear"
  >
    <template v-slot:item="data">
      <template v-if="typeof data.item !== 'object'">
        <v-list-tile-content>
          <v-list-tile-title
            class="text-truncate"
            v-text="data.item"
          />
        </v-list-tile-content>
      </template>
      <template v-else>
        <v-list-tile-content>
          <v-list-tile-title
            class="text-truncate"
            v-text="data.item[itemText] || 'No Label'"
          />
        </v-list-tile-content>
      </template>
    </template>
  </v-autocomplete>
</template>

<script>
import throttle from 'lodash/throttle'
import get from 'lodash/get'
import isEmpty from 'lodash/isEmpty'
import isObject from 'lodash/isObjectLike'

export default {
  /**
     * Please see VAutocomplete for all properties
     * Except for the following:
     *  - `items` is not allowed due to the internally managed list
     */
  props: {
    url: {
      type: String,
      required: true
    },
    termParam: {
      type: String,
      default: 'term'
    },
    placeholder: {
      type: String,
      default: 'Start typing to Search'
    },
    itemText: {
      type: String,
      default: 'name'
    },
    itemValue: {
      type: String,
      default: 'id'
    },
    notClearable: {
      type: Boolean,
      default: false
    },
    value: {
      type: [Object, Array, Number, String],
      default: null
    },
    returnValue: {
      type: Boolean,
      default: false
    },
    /**
       * When true this will use cause fetch to execute when value changes
       */
    valueFetch: {
      type: Boolean,
      default: false
    },
    /**
       * While we could use a generic 'avatarKey' property,
       *  the FUserAvatar is much more advanced for users
       */
    showUserAvatar: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      results: this.$attrs.items || [],
      isLoading: false,
      term: null,
      termCancelToken: null,
      previousTerm: null
    }
  },
  computed: {
    internalItems () {
      let list = this.results
      if (isEmpty(this.results)) {
        list = isObject(this.value) ? [ this.value ] : []
      }
      return list
    }
  },
  watch: {
    term (val) {
      this.$emit('termChange', val)
      this.onTermChange(val)
    },
    '$attrs.items' (value) {
      this.results = value
    },
    value (val) {
      if (this.valueFetch) {
        this.fetchWithKeyValue(val)
      }
    }
  },
  beforeMount () {
    if (this.valueFetch) {
      this.fetchWithKeyValue(this.value)
    }
  },
  methods: {
    onClear () {
      this.clear()
    },
    termUpdate (event) {
      this.term = event.target.value
    },
    clearTerm () {
      this.term = ''
    },
    clear () {
      this.clearTerm()
      this.results = []
    },
    onTermChange: throttle(function () {
      this.fetchWithTerm(this.term)
    }, 300),

    async fetchWithKeyValue (value) {
      // Empty value
      if (!value) {
        this.results = []
        return
      }

      const params = {
        [`filter[${this.itemValue}]`]: value
      }
      return this.fetch(params)
    },

    async fetchWithTerm (value) {
      // Empty term
      if (!value) {
        this.results = []
        return
      }

      // Skip the search
      if (this.previousTerm === value) return

      // Set the last term
      this.previousTerm = value

      // Build params
      const params = {
        [this.termParam]: value
      }
      return this.fetch(params)
    },

    async fetch (params) {
      if (!this.$http) {
        console.error('FAutocomplete cannot fetch without vue-axios plugin installed')
        return
      }
      this.isLoading = true
      let CancelToken = this.$http.CancelToken
      // Cancel any existing requests
      if (this.termCancelToken !== null) this.termCancelToken()
      try {
        const response = await this.$http.get(this.url, {
          params,
          cancelToken: new CancelToken(function executor (c) {
            this.termCancelToken = c
          }.bind(this))
        })
        const noResults = [{
          disabled: true,
          [this.itemText]: 'No match found'
        }]
        const results = get(response, 'data.data', noResults)
        this.results = results.length ? results : noResults
      } finally {
        this.isLoading = false
      }
    }
  }
}
</script>
