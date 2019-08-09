<template>
  <search-filter-sorter
    autocomplete_url="/api/foodfleet/financial-reports"
    :sort-options="sortables"
    without-filter-label
    without-sort-by
    placeholder="Search"
    color="white"
    icon="search"
    raised-term
    @run="run"
  />
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  components: {
    SearchFilterSorter
  },
  methods: {
    run (params) {
      this.$emit('runFilter', {
        name: params.term
      })
    }
  }
}
</script>

<style lang="styl" scoped>
  /deep/ .rounded-input .v-input__slot, /deep/ .filter-sorter-sortable-select .v-input__slot {
    border-radius: 50px !important;
    border: solid 1px #a9a9a9;
  }

  /deep/ .filters-filter {
    margin: 0;
    max-width: 200px;
    flex-basis: auto;
  }
  /deep/ .filters-filter .v-input__control {
    font-size: 14px;
    min-height: 36px;
  }
</style>
