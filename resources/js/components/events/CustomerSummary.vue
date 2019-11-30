<template>
  <v-card>
    <v-container font-weight-bold grey--text subheading py-3>
      Customer Summary
    </v-container>
    <v-divider/>
    <v-container pt-0 font-weight-bold grey--text>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>OWNER</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{customer.owner}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>SIGNED CONTRACTS</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{customer.signed_contracts}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>CONTACT PHONE</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{customer.phone}}</v-flex>
      </v-layout>
      <v-divider/>
      <v-layout align-center line>
        <v-flex caption font-weight-bold>CONTACT EMAIL</v-flex>
        <v-flex text-xs-right subheading xs6 text-truncate>{{customer.email}}</v-flex>
      </v-layout>
      <v-divider/>

      <v-btn block
             class="grey ma-0 mt-4 white--text text-none"
             @click="onButtonClick"
      >
        View Customer Profile
      </v-btn>
    </v-container>
  </v-card>
</template>

<script>

export default {
  props: {
    customer: {
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
