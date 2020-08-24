<template>
  <v-card>
    <v-card-title class="grey--text font-weight-bold">
      Venue Details
    </v-card-title>
    <v-divider />
    <div>
      Map
    </div>
    <v-layout
      row
      wrap
      class="pa-3"
    >
      <v-flex
        xs6
        pr-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Venue
        </div>
        <v-select
          :items="venues"
          item-text="name"
          item-value="uuid"
          single-line
          outline
        />
      </v-flex>
      <v-flex
        xs6
        pl-2
      >
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Location
        </div>
        <v-select
          :items="venues"
          item-text="name"
          item-value="uuid"
          single-line
          outline
        />
      </v-flex>
      <v-flex
        xs12
        pb-3
        class="d-flex"
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Address
        </div>
        <div class="grey--text text-xs-right">
          12345 LA Stadium Way Los Angeles, CA 90210
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider />
      </v-flex>
      <v-flex
        xs12
        py-3
        class="d-flex"
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Capacity
        </div>
        <div
          class="grey--text text-xs-right"
        >
          300 People
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider />
      </v-flex>
      <v-flex
        xs12
        py-3
        class="d-flex"
      >
        <div class="text-uppercase grey--text font-weight-bold">
          Spots
        </div>
        <div
          class="grey--text text-xs-right"
        >
          30
        </div>
      </v-flex>
      <v-flex xs12>
        <v-divider />
      </v-flex>
      <v-flex
        xs12
        py-3
      >
        <div class="pb-2 text-uppercase grey--text font-weight-bold">
          Location details
        </div>
        <div>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam interdum sagittis nibh sed accumsan. Etiam a mauris eget turpis maximus fermentum. Suspendisse eu condimentum...
        </div>
      </v-flex>
      <v-flex
        xs12
      >
        <v-btn
          block
          depressed
          class="ma-0"
        >
          Get Directions
        </v-btn>
      </v-flex>
    </v-layout>
  </v-card>
</template>
<script>
export default {
  props: {
    venues: {
      type: Array,
      default: () => []
    }
  }
}
</script>
