<template>
  <v-layout
    row
    wrap
    justify-space-between
  >
    <v-flex
      md5
      sm12
    >
      <v-select
        v-model="typeValue"
        single-line
        outline
        :items="options"
        data-vv-name="type"
        :error-messages="errors.collect('type')"
      />
    </v-flex>
    <v-flex
      md6
      sm12
    >
      <simple
        ref="searchAssigned"
        :placeholder="`Search ${currentOption.text}`"
        :url="currentOption.url"
        :term-param="currentOption.param"
        v-bind="$attrs"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        @input="selectAssigned"
      />
    </v-flex>
  </v-layout>
</template>

<script>
import Simple from 'fresh-bus/components/search/simple'

export default {
  components: {
    Simple
  },
  props: {
    type: {
      type: Number,
      default: 1
    },
    onAssignChange: {
      type: Function,
      required: true
    },
    onTypeChange: {
      type: Function,
      required: true
    }
  },
  data () {
    return {
      options: [
        {
          value: 1,
          text: 'User',
          key: 'assigned_user_uuid',
          url: 'users',
          param: 'term'
        },
        {
          value: 2,
          text: 'Fleet Member',
          key: 'assigned_fleet_member_uuid',
          url: 'foodfleet/fleet-members',
          param: 'filter[name]'
        },
        {
          value: 3,
          text: 'Venue',
          key: 'assigned_venue_uuid',
          url: 'foodfleet/venues',
          param: 'filter[name]'
        },
        {
          value: 4,
          text: 'Event',
          key: 'assigned_event_uuid',
          url: 'foodfleet/events',
          param: 'filter[name]'
        },
        {
          value: 5,
          text: 'Event/Fleet Mem',
          key: 'assigned_user_uuid',
          url: 'users',
          param: 'term'
        },
        {
          value: 6,
          text: 'Event/Venue',
          key: 'assigned_user_uuid',
          url: 'users',
          param: 'term'
        }
      ]
    }
  },
  computed: {
    currentOption () {
      return this.options.find(item => item.value === this.type)
    },
    typeValue: {
      get: function () {
        return this.type
      },
      set: function (value) {
        if (value !== this.typeValue) {
          this.onTypeChange(value)
          this.$refs.searchAssigned.resetTerm()
          this.onAssignChange('')
        }
      }
    }
  },
  methods: {
    selectAssigned (assigned) {
      this.onAssignChange(assigned ? assigned.uuid : '')
    }
  }
}
</script>
