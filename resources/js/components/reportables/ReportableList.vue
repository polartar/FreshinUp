<template>
  <v-data-table
    v-model="selected"
    class="elevation-1"
    :headers="headers"
    :items="reportables"
    :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
    :pagination.sync="pagination"
    :loading="isLoading"
    :total-items="totalItems"
    item-key="id"
    hide-actions
    select-all
    must-sort
  >
    <template
      slot="headerCell"
      slot-scope="props"
    >
      <span v-if="selected.length > 1 && props.header.value === 'delete'">
        <v-btn
          color="primary"
          dark
          @click="deleteReportables"
        >
          Delete
        </v-btn>
      </span>

      <span v-else>
        {{ props.header.text }}
      </span>
    </template>
    <template
      slot="items"
      slot-scope="props"
    >
      <td>
        <v-checkbox
          v-model="props.selected"
          primary
          hide-details
        />
      </td>
      <td
        class="text-xs-left"
      >
        {{ props.item.name }}
      </td>
      <td>
        <v-layout row>
          <v-flex
            xs6
            mr-1
          >
            <modifier
              v-if="props.item.modifier_1"
              :modifier="props.item.modifier_1"
              :items="getModifierSelectables(props.item.modifier_1)"
              @change="changeModifier1Value($event, props.item)"
            />
          </v-flex>
          <v-flex
            xs6
            ml-1
          >
            <modifier
              v-if="props.item.modifier_2"
              :modifier="props.item.modifier_2"
              :items="getModifierSelectables(props.item.modifier_2)"
              @change="changeModifier2Value($event, props.item)"
            />
          </v-flex>
        </v-layout>
      </td>
      <td class="text-xs-left mb-1">
        {{ formatFilters(props.item.filters) }}
      </td>
      <td class="text-xs-right">
        <a
          class="primary--text open"
          target="_blank"
          :href="report_links[props.item.id]"
        >
          Open in new tab
        </a>
      </td>
      <td class="text-xs-right">
        <v-btn
          color="primary"
          dark
          :href="report_links[props.item.id]"
        >
          Generate
        </v-btn>
      </td>
      <td class="text-xs-right">
        <v-btn
          fab
          icon
          dark
          small
          color="grey"
          outline
          @click="deleteReportable(props.item)"
        >
          <v-icon dark>
            fa-times
          </v-icon>
        </v-btn>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import Pagination from 'fresh-bus/components/mixins/Pagination'
import Modifier from './Modifier'

export default {
  components: {
    Modifier
  },
  mixins: [
    Pagination
  ],
  props: {
    reportables: {
      type: Array,
      default: () => ([])
    },
    selectables: {
      type: Object,
      default: () => ({})
    },
    baseUrl: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      selected: [],
      modifier_1: [],
      modifier_2: [],
      report_links: [],
      headers: [
        { text: 'Report name', sortable: false, value: 'date', align: 'left', class: 'font-weight-bold' },
        { text: 'Modifiers', value: 'modifiers', sortable: false, align: 'left', class: 'font-weight-bold', width: '35%' },
        { text: 'Filters', value: 'filters', sortable: false, align: 'left', class: 'font-weight-bold', width: '10%' },
        { value: 'new_tab', sortable: false },
        { value: 'action', sortable: false },
        { value: 'delete', sortable: false }
      ]
    }
  },
  beforeMount () {
    this.reportables.forEach((element) => {
      this.modifier_1[element.id] = null
      this.modifier_2[element.id] = null
      this.report_links[element.id] = this.reportLink(element)
    })
  },
  methods: {
    reportLink (report) {
      let params = {}
      let modifier1 = this.modifier_1[report.id]
      let modifier2 = this.modifier_2[report.id]
      if (modifier1) params[report.modifier_1.name] = modifier1
      if (modifier2) params[report.modifier_2.name] = modifier2

      for (let i in report.filters) {
        if (report.filters[i] === null) continue

        if (i.indexOf('range') !== -1) {
          let param = i.replace(/_range/g, '')
          params['min_' + param] = report.filters[i][0] == null ? '0' : report.filters[i][0]
          if (report.filters[i][1] != null) params['max_' + param] = report.filters[i][1]
        } else {
          params[i] = report.filters[i] && report.filters[i].value ? report.filters[i].value : report.filters[i]
        }
      }

      let qs = Object.entries(params).map(([key, val]) => `${key}=${val}`).join('&')
      return this.baseUrl + '?' + qs
    },
    getModifierSelectables (modifier) {
      if (modifier && modifier.type === 'select') {
        let mapper = (item) => ({ value: item.uuid, text: item.label })
        let selectables = this.selectables[modifier.resource_name].map(mapper)
        selectables.unshift({ value: null, text: modifier.placeholder })
        return selectables
      }
      return []
    },
    formatFilters (filters) {
      return Object.keys(filters).join(', ').replace(/_/g, ' ').replace(/ id/g, '').replace(/ uuid/g, '').replace(/\b\w/g, l => l.toUpperCase())
    },
    toCamelCase (text) {
      return text.replace(/([-_][a-z])/ig, ($1) => {
        return $1.toUpperCase()
          .replace('-', '')
          .replace('_', '')
      })
    },
    changeModifier1Value (value, report) {
      this.modifier_1[report.id] = value
      this.updateLink(report)
    },
    changeModifier2Value (value, report) {
      this.modifier_2[report.id] = value
      this.updateLink(report)
    },
    updateLink (report) {
      this.$set(this.report_links, report.id, this.reportLink(report))
    },
    deleteReportable (item) {
      this.$emit('delete', item)
    },
    deleteReportables () {
      this.$emit('deleteMultiple', this.selected)
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .v-datatable thead {
    background: var(--v-secondary-base) !important
  }
  /deep/ table.v-table thead tr {
    height: 30px;
  }
  /deep/ table.v-table tbody tr {
    height: 78px;
  }
  /deep/ .v-datatable th {
    color: white !important
  }
  /deep/ .rounded-input .v-input__slot {
    border-radius: 50px !important;
    border: solid 1px #a9a9a9;
  }

  .open:link {
    text-decoration: none;
  }
</style>
