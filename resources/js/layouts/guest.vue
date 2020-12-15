<template>
  <v-container class="ff-guest__container">
    <v-content class="main-page-container">
      <v-container
        v-if="pageTitle"
        fluid
      >
        <h1 class="page-title white--text">
          {{ pageTitle }}
        </h1>
      </v-container>

      <v-progress-linear
        v-if="isLoading"
        :indeterminate="true"
      />
      <router-view v-show="!isLoading" />
      <v-snackbar
        v-model="isVisible"
        color="error"
        :timeout="6000"
        top
      >
        {{ errorMessages }}
        <v-btn
          dark
          flat
          @click="setErrorVisibility(false)"
        >
          Close
        </v-btn>
      </v-snackbar>
      <v-snackbar
        v-model="isMessageVisible"
        color="success"
        :timeout="6000"
        top
      >
        {{ message }}
        <v-btn
          dark
          flat
          @click="setMessageVisibility(false)"
        >
          Close
        </v-btn>
      </v-snackbar>
    </v-content>
    <slot name="footer">
      <fresh-bus-footer />
    </slot>
  </v-container>
</template>

<script>
import Default from './default'

export default {
  extends: Default,
  beforeRouteEnterOrUpdate (vm, to, from, next) {
    this.$auth.logout({
      redirect: false
    })
  }
}
</script>

<style scoped>
  .ff-guest__container {
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
