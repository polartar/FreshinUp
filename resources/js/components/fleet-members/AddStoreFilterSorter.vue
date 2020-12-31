<template>
  <div class="px-4 pt-4">
    <v-text-field
      v-model="name"
      label="Search by Fleet Member name"
      single-line
      outline
    />
    <div>
      <div class="pb-4">
        <button
          class="font-weight-bold grey--text"
          @click="toggleShowFilter"
        >
          <span v-if="showFilters">
            <v-flex>
              <v-icon small>fa-caret-down</v-icon>
              Hide Filters
            </v-flex>
          </span>
          <span v-else>
            <v-flex>
              <v-icon small>fa-caret-right</v-icon>
              Show Filters
            </v-flex>
          </span>
        </button>
      </div>
      <div
        v-show="showFilters"
      >
        <v-layout
          row
          wrap
          align-center
          justify-center
        >
          <v-flex
            sm3
            pr-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label
                class="grey--text"
              >
                State
              </filter-label>
              <clear-button
                v-if="state_of_incorporation"
                color="grey"
                @clear="state_of_incorporation = null"
              />
            </v-layout>
            <v-text-field
              v-model="state_of_incorporation"
              label="State of incorporation"
              outline
              single-line
            />
          </v-flex>
          <v-flex
            sm3
            pr-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label
                class="grey--text"
              >
                Type
              </filter-label>
              <clear-button
                v-if="type_id"
                color="grey"
                @clear="type_id = null"
              />
            </v-layout>
            <v-select
              v-model="type_id"
              :items="types"
              item-value="id"
              item-text="name"
              label="Type"
              outline
              single-line
            />
          </v-flex>
          <v-flex
            sm3
            pr-2
          >
            <v-layout
              row
              justify-space-between
              mb-2
            >
              <filter-label class="grey--text">
                Tags
              </filter-label>
              <clear-button
                v-if="tags && tags.length > 0"
                color="grey"
                @clear="tags = []; $refs.tag.resetTerm()"
              />
            </v-layout>
            <f-multi-simple
              ref="tag"
              v-model="tags"
              url="foodfleet/store-tags"
              term-param="filter[name]"
              results-id-key="uuid"
              placeholder="Search Tag"
              background-color="white"
              class="mt-0 pt-0 mb-4"
              height="48"
              not-clearable
              solo
              ouline
              flat
            />
          </v-flex>
          <v-flex
            sm3
          >
            <v-btn
              large
              depressed
              @click="clearFilters"
            >
              Clear all filters
            </v-btn>
          </v-flex>
        </v-layout>
      </div>
    </div>
  </div>
</template>

<script>
import ClearButton from '~/components/ClearButton'
import FilterLabel from '~/components/FilterLabel'
import FMultiSimple from 'fresh-bus/components/ui/FMultiSimple'
import MapValueKeysToData from '../../mixins/MapValueKeysToData'
export const DEFAULT_FILTERS = {
  name: '',
  state_of_incorporation: '',
  type_id: '',
  tags: []
}
export default {
  components: {
    ClearButton,
    FilterLabel,
    FMultiSimple
  },
  mixins: [MapValueKeysToData],
  props: {
    types: { type: Array, default: () => [] },
    // overriding value props from MapValueKeysToData mixin
    // to provide default value
    value: { type: Object, default: () => DEFAULT_FILTERS }
  },
  data () {
    return {
      showFilters: false,
      ...DEFAULT_FILTERS
    }
  },
  watch: {
    payload () {
      this.run()
    }
  },
  methods: {
    clearFilters () {
      this.name = ''
      this.state_of_incorporation = ''
      this.type_id = ''
      this.tags = []
      this.$refs.tag.resetTerm()
    },
    toggleShowFilter () {
      this.showFilters = !this.showFilters
    },
    run () {
      this.$emit('input', {
        name: this.payload.name,
        state_of_incorporation: this.payload.state_of_incorporation,
        type_id: this.payload.type_id,
        tag: this.tags ? this.tags.map(item => item.uuid) : null
      })
    }
  }
}
</script>
