<template>
  <div>
    <v-divider />
    <div
      v-for="message in messages"
      :key="message.uuid"
    >
      <v-layout
        flex
        px-3
      >
        <v-flex
          py-3
        >
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
        </v-flex>
        <v-flex
          ml-4
          py-3
          class="grey--text"
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
        </v-flex>
      </v-layout>
      <v-divider />
    </div>
  </div>
</template>
<script>
import FormatDate from 'fresh-bus/components/mixins/FormatDate'
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
      type: Array,
      default: () => []
    }
  }
}
</script>
