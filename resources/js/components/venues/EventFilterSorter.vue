<template>
  <search-filter-sorter
    ref="filter"
    class="filter-transparent"
    without-filter-label
    placeholder="Search"
    color="transparent"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
  />
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter'
export default {
  components: {
    SearchFilterSorter
  },
  methods: {
    run (params) {
      this.$emit('runFilter', {
        sort: params.orderBy,
        'filter[name]': params.term
      })
    }
  }
}
</script>
