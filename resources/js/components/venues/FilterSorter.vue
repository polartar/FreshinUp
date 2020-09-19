<template>
  <search-filter-sorter
    without-filter-label
    sort-label="Sort by"
    :sort-options="statuses"
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
    @clear="clearFilters"
  >
  </search-filter-sorter>
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  components: {
    SearchFilterSorter
  },
  props: {
    statuses: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    run (params) {
      let finalParams = {
        title: params.term,
        sort: params.orderBy,
        ...this.filters
      }

      this.$emit('runFilter', finalParams)
    },

    clearFilters (params) {
      this.run(params)
    }
  }
}
</script>

<style lang="styl" scoped>
  /deep/ .data-time-picker.no-border input.field-input {
    border: none !important;
  }
  /deep/ .filter-sorter-expanded-layout {
    align-items: flex-end;
  }
  /deep/ .filter-sorter-expanded-layout > .flex.text-no-wrap > .v-btn {
    margin: 0;
    height: 48px;
  }
  .filter-transparent {
    box-shadow: none;
  }
</style>
