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
                item-value="uuid"
                item-text="name"
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
              <v-select
                v-model="selectedTags"
                :items="tags"
                item-value="uuid"
                item-text="name"
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
      <v-data-table
        v-model="selected"
        :headers="headers"
        :items="filteredMembers"
        :search="searchText"
        item-key="uuid"
        hide-actions
        select-all
        :pagination.sync="pagination"
      >
        <template v-slot:items="props">
          <td>
            <v-checkbox
              v-model="props.selected"
              primary
              hide-details
            />
          </td>
          <td
            class="py-2"
            nowrap
          >
            <router-link
              class="primary--text"
              :to="{ path: '/admin/fleet-members/'}"
              target="_blank"
            >
              {{ get(props, 'item.name') }}
            </router-link>
            <div
              class="grey--text text--darken-2"
            >
              {{ get(props, 'item.type.name') }}
            </div>
          </td>
          <td class="py-2">
            {{ get(props, 'item.location.name') }}
          </td>
          <td class="py-2">
            <div>
              <v-chip
                v-for="(tag, index) of get(props, 'item.store_tags', [])"
                :key="index"
                color="secondary"
                text-color="white"
              >
                {{ tag.name }}
              </v-chip>
            </div>
          </td>
          <td class="py-2">
            <v-btn
              :depressed="manageButtonLabel(props.item) !== 'Assign'"
              :disabled="!itemIsEligible(props.item)"
              :class="manageButtonClass(props.item)"
              @click="onManageClicked(props.item)"
            >
              {{ manageButtonLabel(props.item) }}
            </v-btn>
          </td>
        </template>
      </v-data-table>
      <div class="text-xs-center pt-2">
        <v-pagination
          v-model="pagination.page"
          :length="pages"
        />
      </div>
    </div>
  </div>
</template>
<script>

import _unionBy from 'lodash/unionBy'
import _uniqBy from 'lodash/uniqBy'
import get from 'lodash/get'

export const DocumentStatus = {
  EXPIRED: 5
}

export default {
  props: {
    members: {
      type: Array,
      default: () => []
    },
    event: {
      type: Object,
      default: null
    },
    assignedEvents: {
      type: Array,
      default: () => []
    },
    docStatuses: {
      type: Array,
      default: () => []
    },
    docs: {
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
      headers: [
        { text: 'Fleet member', value: 'name' },
        { text: 'State of incorporation', value: 'location' },
        { text: 'Tags', value: 'store_tags' },
        { text: 'Manage', value: 'assigned' }
      ]
    }
  },
  computed: {
    pages () {
      if (this.pagination.rowsPerPage == null ||
        this.totalItems == null
      ) return 0

      return Math.ceil(this.totalItems / this.pagination.rowsPerPage)
    },

    filteredMembers () {
      let items = this.members

      if (this.selectedState) {
        items = items.filter(i => i['location']['uuid'] === this.selectedState)
      }

      if (this.selectedType) {
        items = items.filter(i => i['type']['id'] === this.selectedType)
      }

      if (this.selectedTags.length) {
        items = items.filter(
          i => i['store_tags']
            .map(t => t['uuid'])
            .some(id => this.selectedTags.includes(id))
        )
      }

      return items
    },

    totalItems () {
      return this.filteredMembers.length
    },

    locations () {
      const def = {
        id: 0,
        uuid: '',
        name: 'All locations'
      }
      return _uniqBy([def, ...this.filteredMembers.map(m => m.location)], 'uuid')
    },

    tags () {
      const def = {
        uuid: '',
        name: 'All tags'
      }
      return _unionBy(def, ...this.filteredMembers.map(m => m['store_tags']), 'uuid')
    },

    types () {
      const def = {
        id: 0,
        name: 'All types'
      }
      return _uniqBy([def, ...this.filteredMembers.map(m => m.type)], 'id')
    },

    /*
    expired status is : { value: 5, text: 'Expired' }
    * */
    expiredDocs () {
      return this.docs.filter(d => d['status'] === DocumentStatus.EXPIRED)
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

    manageButtonLabel (item) {
      if (this.itemIsEligible(item)) {
        if (this.itemIsDeclined(item)) { return 'Declined' }
        if (!this.itemIsAssignedToEvent(item, this.event)) { return 'Assign' } else { return 'Assigned' }
      }

      if (this.itemIsBooked(item)) { return 'Booked' }

      if (this.itemHasExpired(item)) { return 'Expired' }

      return ''
    },

    itemIsEligible (item) {
      return !this.itemIsBooked(item) && !this.itemHasExpired(item)
    },

    /* TODO:
      */
    itemIsAssignedToEvent (item, event) {
      return false
    },

    itemIsBooked (item) {
      const otherEvents = this.event
        ? this.assignedEvents.filter(e => e['uuid'] !== this.event['uuid'])
        : this.assignedEvents
      return otherEvents.some(evt => this.itemIsAssignedToEvent(item, evt))
    },

    itemHasExpired (item) {
      /* TODO: Remains expired licenses
      */
      return this.expiredDocs.some(d => d['assigned']['uuid'] === item['uuid'])
    },

    /* TODO:
      */
    itemIsDeclined (item) {
      return true
    },

    onManageClicked (item) {
      /* TODO
      Handle the action only if the item is assignable
       */
    },

    manageButtonClass (item) {
      return {
        'primary': this.manageButtonLabel(item) === 'Assign',
        'blue-grey lighten-5': ['Expired', 'Booked'].includes(this.manageButtonLabel(item)),
        'blue-grey lighten-3 white--text': ['Declined', 'Assigned'].includes(this.manageButtonLabel(item))
      }
    }
  }
}
</script>
