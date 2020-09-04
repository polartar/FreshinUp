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
                :items="memberTypes"
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
                    {{ get(props, 'item.name') }}
                  </router-link>
                </template>
                View fleet member details (new tab)
              </v-tooltip>
            </div>
            <div
              class="grey--text text--darken-2"
            >
              {{ typeName(get(props, 'item.type_id', 0)) }}
            </div>
          </td>
          <td class="py-2">
            {{ get(props, 'item.state_of_incorporation') }}
          </td>
          <td class="py-2">
            <div>
              <v-chip
                v-for="(tag, index) of get(props, 'item.tags', [])"
                :key="index"
                color="secondary"
                text-color="white"
              >
                {{ tag }}
              </v-chip>
            </div>
          </td>
          <td class="py-2">
            <v-btn
              :depressed="manageButtonLabel(props.item) !== 'Assign'"
              :disabled="!isEligible(props.item)"
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
import get from 'lodash/get'
import _uniq from 'lodash/uniq'

export default {
  props: {
    members: {
      type: Array,
      default: () => []
    },
    event: {
      type: Object,
      required: true
    },
    memberTypes: {
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
        { text: 'State of incorporation', value: 'state_of_incorporation' },
        { text: 'Tags', value: 'tags' },
        { text: 'Manage' }
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
        items = items.filter(i => i['state_of_incorporation'] === this.selectedState)
      }

      if (this.selectedType) {
        items = items.filter(i => i['type_id'] === this.selectedType)
      }

      if (this.selectedTags.length) {
        items = items.filter(i => i['tags'].some(tag => this.selectedTags.includes(tag)))
      }

      return items
    },

    totalItems () {
      return this.filteredMembers.length
    },

    locations () {
      return _uniq(this.members.map(m => m['state_of_incorporation']))
    },

    tags () {
      const ts = []

      this.members.forEach(m => ts.push(...m['tags']))

      return _uniq(ts)
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
      return !!member.events.find(e => e.start_at === this.event.start_at && e.uuid !== this.event.uuid)
    },

    isEligible (member) {
      return !this.hasBookedAnEvent(member) && !member['has_expired_licences_docs']
    },

    isAssignedToThisEvent (member) {
      return !!member.events.find(e => e.uuid === this.event.uuid)
    },

    isDeclined (member) {
      return !!member.events.find(e => e.uuid === this.event.uuid && e.declined)
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
      return {
        'primary': this.manageButtonLabel(item) === 'Assign',
        'blue-grey lighten-5': ['Expired', 'Booked'].includes(this.manageButtonLabel(item)),
        'blue-grey lighten-3 white--text': ['Declined', 'Assigned'].includes(this.manageButtonLabel(item))
      }
    },

    typeName (id) {
      const type = this.memberTypes.find(t => t.id === id)

      return type ? type['name'] : ''
    }
  }
}
</script>
