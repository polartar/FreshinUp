<template>
  <v-card>
    <v-container
      font-weight-bold
      grey--text
      subheading
      py-3
    >
      Event Financial Summary
    </v-container>
    <v-divider />
    <v-container
      pt-0
      font-weight-bold
      grey--text
    >
      <v-layout
        align-center
        line
      >
        <v-flex
          caption
          font-weight-bold
        >
          TOTAL FLEET
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ financial.total_fleet }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        align-center
        line
      >
        <v-flex
          caption
          font-weight-bold
        >
          TOTAL COST
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ financial.total_cost }}
        </v-flex>
      </v-layout>
      <v-divider />
      <v-layout
        align-center
        line
      >
        <v-flex
          caption
          font-weight-bold
        >
          AMOUNT DUE
        </v-flex>
        <v-flex
          text-xs-right
          subheading
          xs6
          text-truncate
        >
          {{ financial.amount_due }}
        </v-flex>
      </v-layout>
      <v-divider />

      <v-btn
        block
        class="grey ma-0 mt-4 white--text text-none"
        @click="onButtonClick"
      >
        View Contract
      </v-btn>
    </v-container>
  </v-card>
</template>

<script>

export default {
  props: {
    financial: {
      type: Object,
      required: true
    }
  },
  methods: {
    onButtonClick: function () {
      this.$emit('onButtonClick')
    }
  }
}
</script>

<style scoped>
  .line {
    height: 40px;
  }
</style>
