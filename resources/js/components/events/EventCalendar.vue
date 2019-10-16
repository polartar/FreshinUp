<template>
  <v-layout wrap>
    <v-flex
      sm4
      xs12
      class="text-xs-center"
    >
      <v-btn>
        Today
      </v-btn>
    </v-flex>
    <v-flex
      sm4
      xs12
      class="text-xs-center"
    >
      <v-select
        v-model="type"
        :items="typeOptions"
        label="Type"
      ></v-select>
    </v-flex>
    <v-flex
      xs12
      class="mb-3"
    >
      <v-sheet height="431">
        <v-calendar
          ref="calendar"
          v-model="start"
          :type="type"
          :end="end"
          color="primary"
        ></v-calendar>
      </v-sheet>
    </v-flex>
  </v-layout>
</template>

<script>

export default {
  props: {
    events: {
      type: Array,
      default: () => []
    },
    type: {
      type: String,
      default: 'month'
    },
    start: {
      type: String,
      default: '2019-01-01'
    },
    end: {
      type: String,
      default: '2019-01-31'
    }
  },
  data: function () {
    return {
      typeOptions: [
        { text: 'Day', value: 'day' },
        { text: '4 Day', value: '4day' },
        { text: 'Week', value: 'week' },
        { text: 'Month', value: 'month' }
      ]
    }
  }
}

</script>