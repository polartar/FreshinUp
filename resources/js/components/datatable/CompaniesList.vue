<template>
  <v-data-table
    v-model="selected"
    class="elevation-1"
    :headers="headers"
    :items="companies"
    :pagination.sync="pagination"
    :rows-per-page-items="[5, 10, 15, 25, 30, 50]"
    :loading="isLoading"
    :total-items="totalItems"
    item-key="id"
    select-all
    must-sort
  >
    <v-progress-linear
      slot="progress"
      indeterminate
      height="10"
    />
    <template
      slot="headerCell"
      slot-scope="props"
    >
      <span v-if="selected.length > 1 && props.header.value === 'manage'">
        <v-menu offset-y>
          <v-btn
            slot="activator"
            color="primary"
            dark
          >
            Manage Multiple
          </v-btn>
          <v-list>
            <v-list-tile
              v-for="(item, index) in selectedCompanyActions"
              :key="index"
              @click="manageMultiple(item.action)"
            >
              <v-list-tile-title>
                {{ item.text }}
              </v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </span>
      <span v-else-if="selected.length > 1 && props.header.value === 'status'">
        <v-menu offset-y>
          <v-btn
            slot="activator"
            light
          >
            Change Statuses
          </v-btn>
          <v-list>
            <v-list-tile
              v-for="(item, index) in statuses"
              :key="index"
              @click="changeStatusMultiple(item.id)"
            >
              <v-list-tile-title>
                {{ item.name }}
              </v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </span>

      <span v-else>
        {{ props.header.text }}
      </span>
    </template>

    <template slot="no-data">
      <v-alert
        :value="true"
        color="error"
        icon="warning"
      >
        Sorry, nothing to display here :(
      </v-alert>
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
      <td class="justify-center text-xs-center">
        <v-select
          :class="`fresh-company__status--${props.item.status}`"
          :items="statuses"
          :value="props.item.status"
          item-text="name"
          item-value="id"
          menu-props="auto"
          label="Status"
          hide-details
          single-line
          solo
          @change="changeStatus($event, props.item)"
        />
      </td>
      <td class="text-xs-left">
        <div class="font-weight-bold mb-1">
          {{ props.item.name }}
        </div>
      </td>
      <td class="text-xs-left">
        <v-chip
          v-for="(type, index) in props.item.company_types"
          :key="index"
        >
          {{ type.name }}
        </v-chip>
      </td>
      <td class="justify-center text-xs-center">
        <span v-if="props.item.members">{{ props.item.members.length }}</span>
      </td>
      <td class="justify-center text-xs-center">
        <v-menu offset-y>
          <v-btn
            slot="activator"
            color="primary"
            dark
          >
            Manage
          </v-btn>
          <v-list>
            <v-list-tile
              v-for="(item, index) in _companyActions(props.item)"
              :key="index"
              @click="manage(item.action, props.item)"
            >
              <v-list-tile-title>
                {{ item.text }}
              </v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </td>
    </template>
  </v-data-table>
</template>

<script>
import CompaniesList from 'fresh-bus/components/datatable/CompaniesList.vue'

export default {
  extends: CompaniesList,

  data () {
    return {
      headers: [
        { text: 'Status', value: 'status', sortable: false, align: 'center' },
        { text: 'Name', value: 'name', align: 'left' },
        { text: 'Company type', value: 'tags', sortable: false, align: 'left' },
        { text: 'Members', value: 'members_count', sortable: true, align: 'center' },
        { text: 'Manage', value: 'manage', sortable: false, align: 'center' }
      ]
    }
  },

  methods: {
    _companyActions (company) {
      // @deprecated
      // return this.companyActions(company).filter(item => item.action !== 'team-add')
      return [
        { action: 'edit', text: 'View / Edit' },
        { action: 'delete', text: 'Delete' }
      ]
    }
  }
}
</script>

<style lang="scss">
.fresh-company__status--1 .v-select__selection {
  color: #71b179 !important;
}
.fresh-company__status--4 .v-select__selection {
  color: #f9ad36 !important;
}
.fresh-company__status--3 .v-select__selection {
  color: #888888 !important;
}
</style>
