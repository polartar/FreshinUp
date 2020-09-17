<template>
  <v-card>
    <v-card-title>
      Basic Information
      <v-progress-linear
        v-if="loading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <v-layout pa-3>
      <v-flex
        xs8
        pr-3
      >
        <v-flex
          xs12
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Venue name
          </div>
          <v-text-field
            placeholder="Name"
            single-line
            outline
          />
        </v-flex>
        <v-flex
          xs12
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Address line 1
          </div>
          <v-text-field
            placeholder="Address 1"
            single-line
            outline
          />
        </v-flex>
        <v-flex
          xs12
        >
          <div class="mb-2 text-uppercase grey--text font-weight-bold">
            Address line 2
          </div>
          <v-text-field
            placeholder="Address 2"
            single-line
            outline
          />
        </v-flex>
        <v-layout row>
          <v-flex xs8>
            <div class="mb-2 text-uppercase grey--text font-weight-bold">
              Owned by
            </div>
            <v-flex
              xs12
              sm6
              md8
              align-center
              layout
            >
              <v-avatar
                :tile="false"
                :size="80"
                color="grey lighten-4"
              >
                <img
                  src="https://vuetifyjs.com/apple-touch-icon-180x180.png"
                  alt="avatar"
                >
              </v-avatar>
              <div class="mx-2 px-2">
                <div class="primary--text subheading">
                  Full Name
                </div>
                <div class="grey--text text--darken-2">
                  Email address
                </div>
                <div class="grey--text text--darken-2">
                  Phone Number
                </div>
              </div>
            </v-flex>
          </v-flex>
          <v-flex
            xs4
            layout
            column
            align-content-center
            justify-center
          >
            <v-btn
              depressed
              disabled
              color="primary"
            >
              Change user
            </v-btn>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex
        xs4
        pl-3
      >
        MAP
      </v-flex>
    </v-layout>
    <v-divider />
    <v-card-actions class="pa-3 d-flex justify-space-between">
      <div class="d-flex">
        <div>
          <v-btn
            depressed
            disabled
          >
            Cancel
          </v-btn>
          <v-btn
            depressed
            disabled
            color="primary"
          >
            Save Changes
          </v-btn>
        </div>
        <div class="text-xs-right">
          <v-btn
            depressed
            disabled
          >
            Delete Venue
          </v-btn>
        </div>
      </div>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  props: {
    loading: { type: Boolean, default: false }
  }
}
</script>
