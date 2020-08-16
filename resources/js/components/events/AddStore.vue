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
            {{ showFilters ? 'Hide' : 'Show' }} Filters
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
                v-model="selectedTag"
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
            <div
              class="primary--text"
              style="font-size: 18px;"
            >
              {{ props.item.name }}
            </div>
            <div
              class="grey--text text--darken-2"
            >
              {{ props.item.type.name }}
            </div>
          </td>
          <td class="py-2">
            {{ props.item.location.name }}
          </td>
          <td class="py-2">
            <div>
              <v-chip
                v-for="(tag, index) of props.item.store_tags"
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
              depressed
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
/* TODO
  It remains:
  - Redirect on fleet member name click
   */
import _unionBy from 'lodash/unionBy'
import _uniqBy from 'lodash/uniqBy'

export default {
  props: {
    members: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      searchText: '',
      selectedState: '',
      selectedType: '',
      selectedTag: [],
      pagination: {
        rowsPerPage: 5,
        page: 1
      },
      page: 1,
      showFilters: true,
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

      if (this.selectedTag.length) {
        items = items.filter(
          i => i['store_tags']
            .map(t => t['uuid'])
            .some(id => this.selectedTag.includes(id))
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
    }
  },
  methods: {
    toggleShowFilter () {
      this.showFilters = !this.showFilters
    },

    clearAllFilters () {
      this.selectedState = ''
      this.selectedType = ''
      this.selectedTag = []
    },

    manageButtonLabel (item) {
      /* TODO
      - 'Assign' = Is eligible, but not yet assigned to current event
      - 'Assigned' = Is eligible, and was assigned to current event
      - 'Declined' = Is eligible, but have declined the assignment
      - 'Booked' = Is not eligible: Already booked for another event at same time / date
      - 'Expired' = Is not eligible: Has expired license or document
       */
      return 'Declined'
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
