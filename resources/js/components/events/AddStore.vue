<template>
  <div>
    <div class="px-4 pt-4">
      <label class="d-block">
        <input
          type="text"
          class="py-2 px-3"
          style="width: 100%; border: 1px solid #dee2e6; border-radius: 5px;"
          placeholder="Search by Fleet Member name"
        >
      </label>
      <div class="my-2 py-2">
        <div>
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
          <div
            class="d-flex align-end justify-space-between pt-2"
          >
            <div class="pr-2">
              <label
                class="d-block text-uppercase font-weight-bold py-2 grey--text text--darken-1"
                for="state"
              >
                state of incorporation
              </label>
              <select
                id="state"
                name="state"
                class="py-2 px-3 rounded"
                style="width: 100%; border: 1px solid #dee2e6;"
              >
                <option
                  value=""
                  selected
                  hidden
                >
                  Select state
                </option>
                <option value="illinois">
                  Illinois
                </option>
              </select>
            </div>
            <div class="pr-2">
              <label
                class="d-block text-uppercase font-weight-bold py-2 grey--text text--darken-1"
                for="type"
              >
                type
              </label>
              <select
                id="type"
                name="type"
                class="py-2 px-3 rounded"
                style="width: 100%; border: 1px solid #dee2e6;"
              >
                <option
                  value=""
                  selected
                  hidden
                >
                  All types
                </option>
                <option value="mobile">
                  Mobile
                </option>
              </select>
            </div>
            <div class="pr-2">
              <label
                class="d-block text-uppercase font-weight-bold py-2 grey--text text--darken-1"
                for="tags"
              >
                tags
              </label>
              <select
                id="tags"
                name="tags"
                class="py-2 px-3 rounded"
                style="width: 100%; border: 1px solid #dee2e6;"
              >
                <option
                  value=""
                  selected
                  hidden
                >
                  Select tags
                </option>
                <option value="tag1">
                  Tag 1
                </option>
                <option value="tag2">
                  Tag 2
                </option>
              </select>
            </div>
            <div>
              <button
                class="px-3 py-2 rounded"
                style="background-color: lightgrey; color: white; width: 100%;"
              >
                Clear all filters
              </button>
            </div>
          </div>
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
        :items="members"
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
  - Searching and filtering
  - Hover on fleet member name
  - Redirect on fleet member name click
  - Tooltip on fleet member name hovered
   */
export default {
  props: {
    members: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
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

    totalItems () {
      return this.members.length
    }
  },
  methods: {
    toggleShowFilter () {
      this.showFilters = !this.showFilters
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
