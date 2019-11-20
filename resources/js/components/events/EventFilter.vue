<template>
  <v-container>
    <v-layout
      wrap
      row
    >
      <v-card
        tile
      >
        <v-card-title class="title font-weight-regular justify-space-between">
          <span size="20">Filter events</span>
        </v-card-title>
        <v-card-text>
          <v-form
            ref="form"
          >
            <v-flex
              md7
              sm12
            >
              <v-layout
                row
                mb-2
              >
                EVENT NAME
              </v-layout>
              <v-text-field
                v-model="name"
                single-line
                outline
              />
            </v-flex>
            <v-flex
              md7
              sm12
            >
              <v-layout
                row
                mb-2
              >
                STATUS
              </v-layout>
              <v-select
                v-model="status"
                single-line
                outline
                :items="statuses"
              />
            </v-flex>
            <v-flex
              md7
              sm12
            >
              <v-layout
                row
                mb-2
              >
                MANAGED BY
              </v-layout>
              <v-select
                v-model="manager"
                single-line
                outline
                :items="managers"
              />
            </v-flex>
            <v-flex
              md7
              sm12
            >
              <v-layout
                row
                mb-2
              >
                CUSTOMER
              </v-layout>
              <v-select
                v-model="customer"
                single-line
                outline
                :items="customers"
              />
            </v-flex>
          </v-form>
        </v-card-text>
      </v-card>
    </v-layout>
  </v-container>
</template>

<script>

export default {
  props: {
    managers: {
      type: Array,
      default: () => []
    },
    customers: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      name: '',
      status: 1,
      manager: null,
      customer: null,
      statuses: [
        { value: 1, text: 'Draft' },
        { value: 2, text: 'Pending' },
        { value: 3, text: 'Confirmed' },
        { value: 4, text: 'Past' },
        { value: 5, text: 'Cancelled' }
      ]
    }
  }
}

</script>

<style>

</style>
