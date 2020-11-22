<template>
  <div>
    <v-divider />
    <div
      v-for="message in messages"
      :key="message.uuid"
    >
      <div
        class="ff-message-list__item"
      >
        <v-flex
          class="pr-2 ff-message-list__item--user"
        >
          <f-user-avatar
            :size="56"
            :user="message.owner"
          />
        </v-flex>
        <v-flex>
          <div
            class="grey--text"
          >
            <div class="subheading font-weight-bold">
              {{ get(message, 'owner.name') }}
            </div>
            <div class="caption mb-4">
              Posted on {{ formatDate(message.created_at, 'MM/DD/YYYY | hh:mm a') }}
            </div>
            <div class="subheading">
              {{ message.content }}
            </div>
          </div>
        </v-flex>
      </div>
      <v-divider />
    </div>
  </div>
</template>
<script>
import FormatDate from '@freshinup/core-ui/src/mixins/FormatDate'
import get from 'lodash/get'
import FUserAvatar from '@freshinup/core-ui/src/components/FUserAvatar'

export default {
  components: {
    FUserAvatar
  },
  mixins: [
    FormatDate
  ],
  props: {
    messages: {
      type: [Array, Object],
      default: () => []
    }
  },
  methods: {
    get
  }
}
</script>

<style lang="scss" scoped>
  .ff-message-list__item {
    display: flex;
    flex-wrap: wrap;
    padding-top: .85rem;
  }

  .ff-message-list__item--user {
    flex: 0 0 auto;
  }

</style>
