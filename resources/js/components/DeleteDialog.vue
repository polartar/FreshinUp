<template>
  <v-dialog
    v-bind="$attrs"
    max-width="500"
    v-on="$listeners"
  >
    <simple-confirm
      :class="{ deleting: isLoading }"
      :title="title"
      ok-label="Yes"
      cancel-label="No"
      @ok="$emit('confirm')"
      @cancel="$emit('cancel')"
    >
      <div class="py-5 px-2">
        <template v-if="isLoading">
          <div class="text-xs-center">
            <p class="subheading">
              Processing, please wait...
            </p>
            <v-progress-circular
              :rotate="-90"
              :size="200"
              :width="15"
              :value="progress"
              color="primary"
            >
              {{ progressStatus }}
            </v-progress-circular>
          </div>
        </template>
        <template v-else>
          <p class="subheading">
            item(s): {{ message }}
          </p>
        </template>
      </div>
    </simple-confirm>
  </v-dialog>
</template>

<script>
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'

export default {
  components: {
    simpleConfirm
  },
  props: {
    itemTitleProp: { type: String, default: 'title' },
    isLoading: { type: Boolean, default: false },
    progress: { type: Number, default: 0 },
    title: {
      type: String,
      default: 'Are you sure you want to delete the selected item(s)'
    },
    items: { type: Array, default: () => [] }
  },
  computed: {
    progressStatus () {
      return `${Math.round(this.progress * 100) / 100} %`
    },
    message () {
      return this.items.map(item => item[this.itemTitleProp]).join(', ')
    }
  }
}
</script>
