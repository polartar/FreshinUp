<template>
  <v-layout
    row
    wrap
    pa-3
    justify-space-between
  >
    <v-flex
      md7
      sm12
    >
      <v-textarea
        v-model="message"
        v-validate="'required'"
        :error-messages="errors.collect('message')"
        data-vv-name="message"
        placeholder="Type Message"
        outline
        single-line
      />
      <v-btn
        class="mr-0 right primary"
        :disabled="!isValid"
        @click="whenValid(send)"
      >
        Send
      </v-btn>
    </v-flex>
  </v-layout>
</template>
<script>
import Validate from 'fresh-bus/components/mixins/Validate'
export default {
  mixins: [Validate],
  data () {
    return {
      message: ''
    }
  },
  methods: {
    send () {
      this.$emit('send-message', this.message)
    }
  }
}
</script>
