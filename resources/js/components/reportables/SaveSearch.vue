<template>
  <v-card class="">
    <v-card-title class="justify-space-between px-4">
      <span class="black--text font-weight-bold title text-uppercase">Save Report</span>
      <v-btn
        icon
        large
        class="mr-0 mt-2 small-btn"
        @click="close"
      >
        <v-icon class="grey--text">
          far fa-times-circle
        </v-icon>
      </v-btn>
    </v-card-title>
    <hr>
    <div class="pa-4">
      <v-form
        ref="form"
        v-model="isValid"
        lazy-validation
      >
        <v-layout
          row
        >
          <v-flex
            sm12
          >
            <v-layout
              row
              justify-space-between
              my-2
            >
              Title
              <clear-button
                v-if="search.name"
                @clear="search.name = null"
              />
            </v-layout>
            <v-text-field
              v-model="search.name"
              v-validate="'required|max:255'"
              solo
              :counter="255"
              data-vv-name="name"
              required
              :error-messages="errors.collect('name')"
            />
          </v-flex>
        </v-layout>
        <v-layout
          row
          mb-2
        >
          <v-flex
            sm6
            :class="{'pr-2': $vuetify.breakpoint.smAndUp}"
          >
            <v-layout
              row
              justify-space-between
              my-2
            >
              Modifier 1
              <clear-button
                v-if="search.modifier_1_id"
                @clear="search.modifier_1_id = null"
              />
            </v-layout>
            <v-select
              v-model="search.modifier_1_id"
              :items="modifierSelectables"
              solo
              hide-details
            />
          </v-flex>
          <v-flex
            sm6
            :class="{'pl-2': $vuetify.breakpoint.smAndUp}"
          >
            <v-layout
              row
              justify-space-between
              my-2
            >
              Modifier 2
              <clear-button
                v-if="search.modifier_2_id"
                @clear="search.modifier_2_id = null"
              />
            </v-layout>
            <v-select
              v-model="search.modifier_2_id"
              :items="modifierSelectables"
              solo
              hide-details
            />
          </v-flex>
        </v-layout>
        <v-layout
          v-if="isPlatformAdmin"
          row
          mt-4
        >
          <v-flex
            sm12
            :class="{'pr-2': $vuetify.breakpoint.smAndUp}"
          >
            <v-checkbox
              v-model="search.featured"
              label="Save as Featured"
              color="primary"
            />
          </v-flex>
        </v-layout>
        <v-layout
          row
          mt-4
          mb-2
        >
          <v-flex>
            <v-btn
              class="ml-0"
              @click="close"
            >
              Cancel
            </v-btn>
          </v-flex>
          <v-flex class="texs-xs-right">
            <v-btn
              class="mr-0 right primary"
              :disabled="!isValid"
              @click="whenValid(save)"
            >
              Save
            </v-btn>
          </v-flex>
        </v-layout>
      </v-form>
    </div>
  </v-card>
</template>

<script>
import ClearButton from '~/components/ClearButton.vue'
import Validate from 'fresh-bus/components/mixins/Validate'

export default {
  components: { ClearButton },
  mixins: [
    Validate
  ],
  props: {
    modifiers: {
      type: Array,
      required: true
    },
    isPlatformAdmin: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      search: {
        name: null,
        modifier_1_id: null,
        modifier_2_id: null,
        featured: false
      }
    }
  },
  computed: {
    modifierSelectables () {
      if (!this.modifiers) return [{ value: null, text: 'None selected' }]

      let modifierSelectables = this.modifiers.map((item) => {
        return { value: item.id, text: item.label }
      })
      modifierSelectables.unshift({ value: null, text: 'None selected' })
      return modifierSelectables
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    save () {
      this.$emit('save', this.search)
    }
  }
}
</script>
<style lang="styl" scoped>
  .v-card {
    border-radius: 6px;
  }
  /deep/ .v-input--checkbox .v-input__slot {
    margin-bottom: 0px;
  }
</style>
