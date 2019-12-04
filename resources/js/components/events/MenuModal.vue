<template>
  <v-dialog
    v-model="dialog"
    max-width="700px"
  >
    <v-card class="pa-2">
      <v-card-title>
        <span class="title font-weight-bold">{{ title }}</span>
      </v-card-title>
      <v-card-text>
        <v-layout
          wrap
          justify-space-between
        >
          <v-flex
            sm5
          >
            <v-layout
              row
              mb-2
            >
              <span class="grey--text font-weight-bold">Item Title</span>
            </v-layout>
            <v-text-field
              v-model="formValue.title"
              v-validate="'required'"
              :error-messages="errors.collect('title')"
              data-vv-name="title"
              placeholder="Enter menu item title"
              solo
              single-line
            />
          </v-flex>
          <v-flex
            sm3
          >
            <v-layout
              row
              mb-2
            >
              <span class="grey--text font-weight-bold">Servings</span>
            </v-layout>
            <v-text-field
              v-model="formValue.servings"
              v-validate="'required'"
              :error-messages="errors.collect('servings')"
              data-vv-name="servings"
              solo
              single-line
            />
          </v-flex>
          <v-flex
            sm3
          >
            <v-layout
              row
              mb-2
            >
              <span class="grey--text font-weight-bold">Cost</span>
            </v-layout>
            <v-text-field
              v-model="formValue.cost"
              v-validate="'required'"
              :error-messages="errors.collect('cost')"
              data-vv-name="cost"
              solo
              single-line
            />
          </v-flex>
          <v-flex
            sm12
          >
            <v-layout
              row
              mb-2
            >
              <span class="grey--text font-weight-bold">Item Description </span>
            </v-layout>
            <v-textarea
              v-model="formValue.description"
              v-validate="'required'"
              :error-messages="errors.collect('cost')"
              data-vv-name="cost"
              placeholder="Enter menu item description"
              solo
              single-line
            />
          </v-flex>
        </v-layout>
      </v-card-text>

      <v-card-actions>
        <v-btn
          block
          @click="cancel"
        >
          Cancel
        </v-btn>

        <v-btn
          color="primary"
          block
          :disabled="!isValid"
          @click="whenValid(save)"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Validate from 'fresh-bus/components/mixins/Validate'
export default {
  mixins: [Validate],
  model: {
    prop: 'value',
    event: 'change'
  },
  props: {
    value: {
      type: Boolean,
      default: false
    },
    menu: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      formValue: { ...this.menu }
    }
  },
  computed: {
    dialog: {
      get: function () {
        return this.value
      },
      set: function (val) {
        this.$emit('change', val)
      }
    },
    title () {
      return this.menu.id ? 'Edit menu item' : 'Add new menu item'
    }
  },
  watch: {
    value (val) {
      this.dialog = val
    },
    menu: {
      handler () {
        this.formValue = { ...this.menu }
      },
      deep: true
    }
  },
  methods: {
    cancel () {
      this.$emit('change', false)
      this.formValue = {}
    },
    save () {
      this.$emit('save', this.formValue)
    }
  }
}
</script>
