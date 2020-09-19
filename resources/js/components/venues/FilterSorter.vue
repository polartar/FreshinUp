<template>
  <v-layout>
    <v-flex>
      <search-filter-sorter
        without-filter-label
        without-sort-by
        v-bind="$attrs"
        v-on="$listeners"
        @run="run"
        @clear="clearFilters"
      />
    </v-flex>
    <v-flex>
      <f-btn-status
        :items="statuses"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import SearchFilterSorter from 'fresh-bus/components/search/filter-sorter.vue'
import FBtnStatus from '@freshinup/core-ui/src/components/FBtnStatus'

export default {
  components: {
    SearchFilterSorter,
    FBtnStatus
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
