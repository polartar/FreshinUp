<template>
  <search-filter-sorter
    ref="searchFilter"
    :autocomplete_url="autocompleteUrl"
    sort-label="Sort by"
    :sort-options="sortables"
    :filters="filters"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
    @clear="clearFilters"
  >
    <template
      v-slot:expanded="slotProps"
    >
      <v-card-text>
        <v-layout
          row
          wrap
        >
          <v-flex lg4>
            <v-layout
              row
            >
              <v-flex shrink>
                <v-card-text>Company Type</v-card-text>
              </v-flex>
              <v-flex>
                <v-select
                  v-model="type"
                  :items="types"
                  item-text="name"
                  item-value="id"
                  placeholder="All Types"
                  solo
                  flat
                  hide-details
                  multiple
                  @change="slotProps.run"
                />
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex lg4>
            <v-layout
              row
            >
              <v-flex shrink>
                <v-card-text>Status</v-card-text>
              </v-flex>
              <v-flex>
                <v-select
                  v-model="status"
                  :items="statuses"
                  item-text="name"
                  item-value="id"
                  placeholder="All"
                  solo
                  flat
                  hide-details
                  @change="slotProps.run"
                />
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex lg4>
            <v-layout
              row
            >
              <v-flex shrink>
                <v-card-text>Owner</v-card-text>
              </v-flex>
              <v-flex>
                <f-simple
                  url="foodfleet/company-owners"
                  term-param="filter[first_name]"
                  placeholder="All"
                  background-color="white"
                  class="mt-0 pt-0"
                  height="48"
                  @input="selectOwner"
                />
              </v-flex>
            </v-layout>
          </v-flex>
        </v-layout>
      </v-card-text>
    </template>
  </search-filter-sorter>
</template>

<script>
import CompaniesFilter from 'fresh-bus/components/companies/FilterSorter.vue'
import FSimple from 'fresh-bus/components/search/simple.vue'

export default {
  extends: CompaniesFilter,
  data() {
    return {
      owner: null
    }
  },
  components: {
    FSimple
  },
  methods: {
    run (params) {
      let paramsObject = {
        sort: params.orderBy,
        'filter[name]': params.term,
        'filter[status]': this.filters.status,
        'filter[users_id]': this.owner
      }
      if (this.filters.type != null && this.filters.type.length) {
        paramsObject['filter[type]'] = this.filters.type.join(',')
      }
      this.$emit('runFilter', paramsObject)
    },
    selectOwner (owner) {
      this.owner = owner ? owner.id : null
      this.$refs.searchFilter.run()
    }
  }
}
</script>