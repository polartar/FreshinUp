<template>
  <v-dialog
    v-bind="$attrs"
    max-width="500"
    v-on="$listeners"
  >
    <simple-confirm
      :class="{ deleting: processing }"
      :title="dialogTitle"
      ok-label="Yes"
      cancel-label="No"
      @ok="onSubmitDelete"
      @cancel="onCancelDelete"
    >
      <div class="py-5 px-2">
        <template v-if="processing">
          <div class="text-xs-center">
            <p class="subheading">
              Processing, please wait...
            </p>
            <v-progress-circular
              :rotate="-90"
              :size="200"
              :width="15"
              :value="deletablesProgress"
              color="primary"
            >
              {{ deletablesStatus }}
            </v-progress-circular>
          </div>
        </template>
        <template v-else>
          <p class="subheading">
            <span v-if="deletables.length < 2">Document</span>
            <span v-else>Documents</span>
            : {{ deleteTemp | formatDeleteTitles }}
          </p>
        </template>
      </div>
    </simple-confirm>
  </v-dialog>
</template>

<script>
// TODO: replace with the one under components/DeleteDialog
import { deletables } from 'fresh-bus/components/mixins/Deletables'
import simpleConfirm from 'fresh-bus/components/SimpleConfirm.vue'
export default {
  components: {
    simpleConfirm
  },
  filters: {
    formatDeleteTitles (value) {
      return value.map(item => item.title).join(', ')
    }
  },
  mixins: [deletables],
  props: {
    processing: {
      type: Boolean,
      default: false
    },
    dialogTitle: {
      type: String,
      default: 'Are you sure you want to delete the selected item(s)'
    },
    deleteTemp: {
      type: Array,
      default: () => []
    },
    onSubmitDelete: {
      type: Function,
      required: true
    },
    onCancelDelete: {
      type: Function,
      required: true
    }
  }
}
</script>
