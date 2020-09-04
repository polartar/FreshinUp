// TODO: extract to core-ui
// This is being used in both Smartmotors and FoodFleet

import keys from 'lodash/keys'
import pick from 'lodash/pick'
import isUndefined from 'lodash/isUndefined'

export default {
  props: {
    value: {
      type: Object,
      default: () => ({ })
    }
  },
  watch: {
    value (value) {
      if (value) {
        this.mapValueKeysToData(value)
      }
    }
  },
  mounted () {
    if (this.value) {
      this.mapValueKeysToData(this.value)
    }
  },
  methods: {
    mapValueKeysToData (value) {
      keys(value).forEach(key => {
        if (!isUndefined(value[key])) {
          this[key] = value[key]
        }
      })
    },
    save () {
      this.$emit('input', pick(this, keys(this.value)))
    }
  }
}
