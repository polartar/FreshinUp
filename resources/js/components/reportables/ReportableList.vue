<template>
  <v-data-table
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
      slot="items"
      slot-scope="{ item }"
    >
      <td
        class="text-xs-left"
      >

      </td>
      <td>
        <v-layout row>
          <v-flex
            xs6
            mr-1
          >
            <template v-if="item.modifier_1.type === 'autocomplete'">
              <v-select
                v-if="item.modifier_1"
                v-model="modifier_1[item.id]"
                :items="getModifierSelectables(item.modifier_1)"
                :placeholder="item.modifier_1.placeholder"
                solo
                flat
                hide-details
                class="rounded-input"
              />
            </template>
            <template v-else-if="modifierType(item.modifier_1) === 'date'">
              <v-menu
                v-model="modifierDateMenus[1][item.id]"
                :close-on-content-click="false"
                transition="scale-transition"
                full-width
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="modifier_1[item.id]"
                    :placeholder="item.modifier_1.placeholder"
                    readonly
                    solo
                    flat
                    hide-details
                    class="rounded-input"
                    append-icon="event"
                    v-on="on"
                  />
                </template>
                <v-date-picker
                  v-model="modifier_1[item.id]"
                  no-title
                  @input="modifierDateMenus[1][item.id] = false"
                />
              </v-menu>
            </template>
          </v-flex>
          <v-flex
            xs6
            ml-1
          >
            <template v-if="modifierType(item.modifier_2) === 'select'">
              <v-select
                v-if="item.modifier_2"
                v-model="modifier_2[item.id]"
                :items="getModifierSelectables(item.modifier_2)"
                :placeholder="item.modifier_2.placeholder"
                solo
                flat
                hide-details
                class="rounded-input"
              />
            </template>
            <template v-else-if="modifierType(item.modifier_2) === 'date'">
              <v-menu
                v-model="modifierDateMenus[2][item.id]"
                :close-on-content-click="false"
                transition="scale-transition"
                full-width
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="modifier_2[item.id]"
                    :placeholder="item.modifier_2.placeholder"
                    readonly
                    solo
                    flat
                    hide-details
                    class="rounded-input"
                    append-icon="event"
                    v-on="on"
                  />
                </template>

                <v-date-picker
                  v-model="modifier_2[item.id]"
                  no-title
                  @input="modifierDateMenus[2][item.id] = false"
                />
              </v-menu>
            </template>
          </v-flex>
        </v-layout>
      </td>
      <td class="text-xs-left mb-1">
        {{ formatFilters(item.filters) }}
      </td>
      <td class="text-xs-right">
        <a
          class="primary--text open"
          target="_blank"
          :href="reportLink(item)"
        >
          Open in new tab
        </a>
      </td>
      <td class="text-xs-right">
        <v-btn
          color="primary"
          dark
          :href="reportLink(item)"
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
          @click="deleteReportable(item)"
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
import Simple from 'fresh-bus/components/search/simple'

export default {
  components: {
    Simple
  },
  mixins: [
    Pagination
  ],
  props: {
    reportables: {
      type: Array,
      default: () => []
    },
    baseUrl: {
      type: String,
      required: true
    }
  },
  data () {
    let modifierDateMenus = { 1: {}, 2: {} }
    this.reportables.forEach((element) => {
      modifierDateMenus[1][element.id] = false
      modifierDateMenus[2][element.id] = false
    })

    return {
      selected: [],
      modifierDateMenus,
      modifier_1: [],
      modifier_2: [],
      headers: [
        { text: 'Report name', sortable: false, value: 'date', align: 'left', class: 'font-weight-bold' },
        { text: 'Modifiers', value: 'modifiers', sortable: false, align: 'left', class: 'font-weight-bold' },
        { text: 'Filters', value: 'filters', sortable: false, align: 'left', class: 'font-weight-bold', width: '25%' },
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
    })
  },
  methods: {
    modifierType (modifier) {
      if (modifier && (modifier.resource_name.endsWith('_before') || modifier.resource_name.endsWith('_after'))) return 'date'
      return 'select'
    },
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
      if (modifier) {
        let mapper = (item) => ({ value: item.uuid, text: item.label })

        if (modifier.resource_name === 'branches') {
          mapper = (branch) => ({ value: branch.id, text: branch.title })
        }

        let selectables = this[this.toCamelCase(modifier.resource_name)].map(mapper)
        selectables.unshift({ value: null, text: modifier.placeholder })
        return selectables
      }
      return null
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
    deleteReportable (item) {
      this.$emit('delete', item)
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
