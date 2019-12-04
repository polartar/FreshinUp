<template>
  <v-layout>
    <v-card width="100%">
      <v-card-title class="justify-space-between px-4">
        <span class="black--text font-weight-bold title text-uppercase">Event Activity</span>
      </v-card-title>
      <hr>
      <div class="pa-4">
        <v-layout
          row
        >
          <v-flex>
            <message-list
              :messages="messages"
            />
          </v-flex>
        </v-layout>
        <v-layout
          row
        >
          <v-flex>
            <message-send
              @send-message="send"
            />
          </v-flex>
        </v-layout>
      </div>
    </v-card>
  </v-layout>
</template>

<script>
import MessageList from './MessageList.vue'
import MessageSend from './MessageSend.vue'

export default {
  components: {
    MessageList,
    MessageSend
  },
  props: {
    messages: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    send (params) {
      this.$emit('send-message', params)
    }
  }
}
</script>

<style scoped>
</style>
