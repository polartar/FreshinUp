<template>
  <v-layout>
    <v-card width="100%">
      <div class="pa-4">
        <v-layout
          row
          class="message-title"
        >
          <span class="grey--text">{{ activists }}</span>
        </v-layout>
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
    activists: {
      type: String,
      default: () => "Activists' Name"
    },
    messages: {
      type: [Array, Object],
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
.message-title {
  padding-bottom: 15px;
}
</style>
