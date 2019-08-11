<template>
  <div>
    <template v-if="modifier.type === 'autocomplete'">
      <simple
        :url="'/' + modifier.resource_name"
        :term-param="modifier.filter"
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
      <v-menu
        v-model="modifierDateMenu"
        :close-on-content-click="false"
        transition="scale-transition"
        full-width
        min-width="290px"
      >
        <template v-slot:activator="{ on }">
          <v-text-field
            v-model="value"
            :placeholder="modifier.placeholder"
            readonly
            solo
            flat
            hide-details
            class="rounded-input"
            append-icon="event"
            v-on="on"
          />
        </template>
        <v-date-picker
          v-model="value"
          no-title
          @input="modifierDateMenu = false; selectValue(value)"
        />
      </v-menu>
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

export default {
  components: {
    Simple
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

</style>
