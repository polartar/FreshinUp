<template>
  <div>
    <v-divider />
    <div
      v-for="message in messages"
      :key="message.uuid"
    >
      <v-layout
        row
        justify-start
        pa-3
      >
        <div>
          <v-avatar
            size="56"
            color="primary"
          >
            <img
              v-if="message.owner && message.owner.avatar"
              :src="message.owner.avatar"
              alt="Avatar"
            >
            <span
              v-else
              class="white--text"
            >
              {{ message.owner && message.owner.name | formatName }}
            </span>
          </v-avatar>
        </div>
        <div
          class="ml-4 grey--text"
        >
          <div class="subheading font-weight-bold">
            {{ message.owner && message.owner.name }}
          </div>
          <div class="caption mb-4">
            Posted on {{ formatDate(message.created_at, 'MM/DD/YYYY | hh:mm a') }}
          </div>
          <div class="subheading">
            {{ message.content }}
          </div>
        </div>
      </v-layout>
      <v-divider />
    </div>
  </div>
</template>
<script>
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
export default {
  filters: {
    formatName (value) {
      if (!value) return ''
      let arr = value.toString().split(' ')
      return arr.map(item => item.charAt(0).toUpperCase()).join('')
    }
  },
  mixins: [
    FormatDate
  ],
  props: {
    messages: {
      type: [Array, Object],
      default: () => []
    }
  }
}
</script>
