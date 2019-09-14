<template>
  <div>
    <template v-if="modifier.type === 'autocomplete'">
      <simple
        :url="'/' + modifier.resource_name"
        :term-param="modifier.filter"
        :results-text-key="modifier.text_param"
        :placeholder="modifier.placeholder"
        background-color="white"
        class="mt-0 pt-0"
        height="48"
        @input="selectAutocomplete"
      />
    </template>
    <template v-else-if="modifier.type === 'select'">
      <v-select
        v-model="value"
        :items="items"
        :placeholder="modifier.placeholder"
        solo
        flat
        hide-details
        @change="selectValue(value)"
      />
    </template>
    <template v-else-if="modifier.type === 'date'">
      <vue-ctk-date-time-picker
        v-model="value"
        only-date
        format="YYYY-MM-DD"
        formatted="MM-DD-YYYY"
        input-size="lg"
        :label="modifier.label"
        :color="$vuetify.theme.primary"
        :button-color="$vuetify.theme.primary"
        @input="selectValue(value)"
      />
    </template>
    <template v-else-if="modifier.type === 'text'">
      <v-text-field
        v-model="value"
        :placeholder="modifier.placeholder"
        solo
        flat
        hide-details
        @input="selectValue(value)"
      />
    </template>
  </div>
</template>

<script>
import Simple from 'fresh-bus/components/search/simple'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css'

export default {
  components: {
    Simple, VueCtkDateTimePicker
  },
  props: {
    modifier: {
      type: Object,
      default: () => ({})
    },
    items: {
      type: Array,
      default: () => ([])
    }
  },
  data () {
    return {
      value: null,
      modifierDateMenu: false
    }
  },
  methods: {
    selectAutocomplete (object) {
      if (object) {
        this.selectValue(object.uuid)
      } else {
        this.selectValue(null)
      }
    },
    selectValue (value) {
      this.$emit('change', value)
    }
  }
}
</script>
<style lang="styl" scoped>
  /deep/ .v-autocomplete .v-input__slot {
    padding: 0 12px
  }
</style>
