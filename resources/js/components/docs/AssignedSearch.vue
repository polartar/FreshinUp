<template>
  <v-layout
    row
    justify-space-between
  >
    <v-flex>
      <v-select
        v-model="typeValue"
        single-line
        solo
        flat
        height="48"
        :items="options"
        hide-details
        data-vv-name="type"
      />
    </v-flex>
    <v-flex ml-4>
      <simple
        ref="assigned"
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
          url: 'foodfleet/venues',
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
          url: 'foodfleet/stores',
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
          this.$emit('assign-change', '')
          if (this.$refs.assigned.resetTerm) {
            this.$refs.assigned.resetTerm()
          }
        }
      }
    }
  },
  methods: {
    selectAssigned (assigned) {
      this.$emit('assign-change', assigned ? assigned.uuid : '')
    }
  }
}
</script>
