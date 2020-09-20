<template>
  <search-filter-sorter
    without-filter-label
    v-bind="$attrs"
    v-on="$listeners"
    @run="run"
    @clear="clearFilters"
  />
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'

export default {
  components: {
    SearchFilterSorter
  },
  props: {
    statuses: { type: Array, default: () => [] }
  },
  methods: {
    run (params) {
      this.$emit('runFilter', params)
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
