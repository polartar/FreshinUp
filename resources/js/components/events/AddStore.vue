<template>
  <div>
    <div class="px-4 pt-4">
      <v-text-field
        v-model="searchText"
        label="Search by Fleet Member name"
        single-line
        outline
      />
      <div>
        <div class="pb-4">
          <button
            style="font-weight: bold; color: lightslategray; outline: 0;"
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
          >
            <v-flex
              sm3
              pr-2
            >
              <v-select
                v-model="selectedState"
                :items="locations"
                label="State of incorporation"
                outline
                single-line
              />
            </v-flex>
            <v-flex
              sm3
              pr-2
            >
              <v-select
                v-model="selectedType"
                :items="storeTypes"
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
              <v-select
                v-model="selectedTags"
                :items="tags"
                label="Tags"
                outline
                single-line
                multiple
              />
            </v-flex>
            <v-flex
              sm3
            >
              <v-btn
                large
                depressed
                @click="clearAllFilters"
              >
                Clear all filters
              </v-btn>
            </v-flex>
          </v-layout>
        </div>
      </div>
    </div>
    <div
      class="px-4"
      style="border-top: 1px solid gainsboro;"
    >
      <f-data-table
        v-model="selected"
        :headers="headers"
        :items="filteredStores"
        :search="searchText"
        item-key="uuid"
        hide-actions
        select-all
      >
        <template v-slot:item-inner-name="{ item }">
          <div style="position: relative;">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <router-link
                  class="primary--text"
                  :to="{ path: '/admin/fleet-members/'}"
                  target="_blank"
                  v-bind="attrs"
                  v-on="on"
                >
                  {{ get(item, 'name') }}
                </router-link>
              </template>
              View fleet member details (new tab)
            </v-tooltip>
          </div>
        </template>
        <template v-slot:item-inner-tags="{ item }">
          <div>
            <v-chip
              v-for="(tag, index) of get(item, 'tags', [])"
              :key="index"
              color="secondary"
              text-color="white"
            >
              {{ tag.name }}
            </v-chip>
          </div>
        </template>
        <template v-slot:item-inner-state_of_incorporation="{ item }">
          <div class="grey--text">
            {{ get(item, 'state_of_incorporation') }}
          </div>
        </template>
        <template v-slot:item-inner-manage="{ item }">
          <v-btn
            :depressed="manageButtonLabel(item) !== 'Assign'"
            :disabled="!isEligible(item)"
            :class="manageButtonClass(item)"
            @click="onManageClicked(item)"
          >
            {{ manageButtonLabel(item) }}
          </v-btn>
        </template>
      </f-data-table>
    </div>
  </div>
</template>
<script>
import get from 'lodash/get'
import _uniq from 'lodash/uniq'
import FDataTable from '@freshinup/core-ui/src/components/FDataTable'

export const HEADERS = [
  { text: 'Fleet member', value: 'name' },
  { text: 'State of incorporation', value: 'state_of_incorporation' },
  { text: 'Tags', value: 'tags' },
  { text: 'Manage', value: 'manage' }
]
export default {
  components: { FDataTable },
  props: {
    stores: {
      type: Array,
      default: () => []
    },
    event: {
      type: Object,
      required: true
    },
    storeTypes: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      searchText: '',
      selectedState: '',
      selectedType: '',
      selectedTags: [],
      pagination: {
        rowsPerPage: 5,
        page: 1
      },
      page: 1,
      showFilters: false,
      selected: [],
      headers: HEADERS
    }
  },
  computed: {
    pages () {
      if (this.pagination.rowsPerPage == null ||
          this.totalItems == null
      ) return 0

      return Math.ceil(this.totalItems / this.pagination.rowsPerPage)
    },

    filteredStores () {
      let items = this.stores
      if (this.selectedState) {
        items = items.filter(store => store['state_of_incorporation'] === this.selectedState)
      }
      if (this.selectedType) {
        items = items.filter(store => store['type_id'] === this.selectedType)
      }
      if (this.selectedTags.length) {
        items = items.filter(store => store['tags'].some(tag => this.selectedTags.findIndex(t => t.uuid === tag.uuid) !== -1))
      }
      return items
    },
    totalItems () {
      return this.filteredStores.length
    },
    locations () {
      return _uniq(this.stores.map(s => s['state_of_incorporation']))
    },
    tags () {
      const tags = []
      this.stores.forEach(store => tags.push(...get(store, 'tags', [])))
      return _uniq(tags.map(tag => tag.name))
    },
    storeTypesById () {
      return this.storeTypes.reduce((map, type) => {
        map[type.id] = type
        return map
      }, {})
    }
  },
  methods: {
    get,
    toggleShowFilter () {
      this.showFilters = !this.showFilters
    },

    clearAllFilters () {
      this.selectedState = ''
      this.selectedType = ''
      this.selectedTags = []
    },
    hasBookedAnEvent (member) {
      return member.events.findIndex(e => e.uuid !== this.event.uuid) !== -1
    },
    isEligible (member) {
      return !this.hasBookedAnEvent(member) && !member['has_expired_licences_docs']
    },
    isAssignedToThisEvent (member) {
      return member.events.findIndex(e => e.uuid === this.event.uuid) !== -1
    },
    isDeclined (member) {
      return member.events.findIndex(e => e.uuid === this.event.uuid && e.declined) !== -1
    },
    manageButtonLabel (item) {
      if (this.isEligible(item)) {
        if (this.isDeclined(item)) { return 'Declined' }
        if (!this.isAssignedToThisEvent(item)) { return 'Assign' } else { return 'Assigned' }
      }

      if (this.hasBookedAnEvent(item)) { return 'Booked' }

      if (item['has_expired_licences_docs']) { return 'Expired' }

      return ''
    },
    onManageClicked (item) {
      if (this.manageButtonLabel(item) === 'Assign') {
        this.$emit('assign', item)
      }
    },
    manageButtonClass (item) {
      const label = this.manageButtonLabel(item)
      return {
        'primary': label === 'Assign',
        'blue-grey lighten-5': ['Expired', 'Booked'].includes(label),
        'blue-grey lighten-3 white--text': ['Declined', 'Assigned'].includes(label)
      }
    }
  }
}
</script>
