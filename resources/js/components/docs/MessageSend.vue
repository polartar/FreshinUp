<template>
  <v-layout
    row
    wrap
    pa-3
    justify-space-between
  >

    <v-flex
      md7
      sm12
    >

      <v-textarea
        v-model="doc.description"
        v-validate="'required'"
        outline
        data-vv-name="description"
        :error-messages="errors.collect('description')"
        label="Type Message"
      />
       <v-btn
        class="mr-0 right primary"
        :disabled="!isValid"
        @click="whenValid(save)"
      >
        Send
      </v-btn>   
    </v-flex>
  </v-layout>
</template>
<script>
import Validate from 'fresh-bus/components/mixins/Validate'
export default {
  components: {
    
  },
  mixins: [Validate],
  props: {
    types: {
      type: Array,
      required: true
    },
    templates: {
      type: Array,
      default: () => []
    },
    initdata: {
      type: Object,
      default: () => {
        return {
          type: null,
          description: null,
          notes: null,
          template: null,
          file: { name: null, src: null }
        }
      }
    }
  },
  data () {
    return {
      doc: this.initdata
    }
  },
  watch: {
    doc: {
      handler (val) {
        this.$emit('data-change', val)
      },
      deep: true
    }
  }
}
</script>
