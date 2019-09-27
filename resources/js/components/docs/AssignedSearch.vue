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
    }
  },
  data () {
    return {
      options: [
        {
          value: 1,
          text: 'User',
          url: 'users',
          param: 'term'
        },
        {
          value: 2,
          text: 'Fleet Member',
          url: 'foodfleet/stores',
          param: 'filter[name]'
        },
        {
          value: 3,
          text: 'Venue',
          url: 'foodfleet/locations',
          param: 'filter[name]'
        },
        {
          value: 4,
          text: 'Event',
          url: 'foodfleet/events',
          param: 'filter[name]'
        },
        {
          value: 5,
          text: 'Event/Fleet Memeber',
          url: 'foodfleet/events',
          param: 'filter[name]'
        },
        {
          value: 6,
          text: 'Event/Venue',
          url: 'foodfleet/events',
          param: 'filter[name]'
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
          this.$emit('type-change', value)
          this.$refs.searchAssigned.resetTerm()
          this.$emit('assign-change', '')
        }
      }
    }
  },
  methods: {
    selectAssigned (assigned) {
      let changeDate = {}
      changeDate.uuid = assigned ? assigned.uuid : ''
      changeDate.event_location_uuid = ''
      changeDate.event_store_uuid = ''

      if (this.type === 5) {
        changeDate.event_store_uuid = assigned.stores[0] ? assigned.stores[0].uuid : ''
      }
      if (this.type === 6) {
        changeDate.event_location_uuid = assigned.location ? assigned.location.uuid : ''
      }
      this.$emit('assign-change', changeDate)
    }
  }
}
</script>
