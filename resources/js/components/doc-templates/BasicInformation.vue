<template>
  <v-card>
    <v-card-title>
      <h3 class="grey--text">
        Basic Information
      </h3>
      <v-progress-linear
        v-if="loading"
        indeterminate
      />
    </v-card-title>
    <v-divider />
    <div class="pa-3">
      <v-flex xs12>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Title
        </div>
        <v-text-field
          placeholder="Title"
          single-line
          outline
        />
      </v-flex>
      <v-flex xs12>
        <div class="mb-2 text-uppercase grey--text font-weight-bold">
          Short description
        </div>
        <v-textarea
          placeholder="Short description"
          single-line
          outline
        />
      </v-flex>
    </div>
  </v-card>
</template>

<script>
export default {
  props: {
    loading: { type: Boolean, default: false }
  }
}
</script>

<style scoped>

</style>
