<template>
  <v-layout
    row
    justify-space-between
  >
    <v-flex
      md6
      sm12
    >
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
    <v-flex
      md6
      sm12
      ml-4
    >
      <DocSimple
        ref="assigned"
        :placeholder="`Search ${currentOption.text}`"
        :url="currentOption.url"
        :term-param="currentOption.param"
        :results-id-key="currentOption.idKey"
        :format-items="currentOption.formatItems"
        :results-text-key="currentOption.textKey"
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
import DocSimple from '~/components/docs/DocSimple'

export default {
  components: {
    DocSimple
  },
  props: {
    type: {
      type: Number,
      default: 1
    }
  },
  data () {
    const formatEventStore = list => {
      let result = []
      list.forEach(event => {
        result = result.concat(
          event.stores.map(store => {
            return {
              uuid: event.uuid,
              event_store_uuid: store.uuid,
              event_store_name: `${event.name}/${store.name}`
            }
          })
        )
      })
      return result
    }
    return {
      options: [
        {
          value: 1,
          text: 'User',
          url: 'users',
          param: 'term',
          idKey: 'uuid',
          textKey: 'name',
          formatItems: null
        },
        {
          value: 2,
          text: 'Fleet Member',
          url: 'foodfleet/stores',
          param: 'filter[name]',
          idKey: 'uuid',
          textKey: 'name',
          formatItems: null
        },
        {
          value: 3,
          text: 'Venue',
          url: 'foodfleet/venues',
          param: 'filter[name]',
          idKey: 'uuid',
          textKey: 'name',
          formatItems: null
        },
        {
          value: 4,
          text: 'Event',
          url: 'foodfleet/events',
          param: 'filter[name]',
          idKey: 'uuid',
          textKey: 'name',
          formatItems: null
        },
        {
          value: 5,
          text: 'Event/Fleet Member',
          url: 'foodfleet/events',
          param: 'filter[name]',
          idKey: 'uuid',
          textKey: 'event_store_name',
          formatItems: formatEventStore
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
          this.resetTerm()
        }
      }
    }
  },
  methods: {
    resetTerm () {
      if (this.$refs.assigned.resetTerm) {
        this.$refs.assigned.resetTerm()
      }
    },
    selectAssigned (assigned) {
      let changeDate = {
        uuid: '',
        event_store_uuid: ''
      }
      changeDate = assigned ? { ...changeDate, ...assigned } : changeDate
      this.$emit('assign-change', changeDate)
    }
  }
}
</script>
