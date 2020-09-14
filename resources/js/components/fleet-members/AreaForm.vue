<template>
  <div>
    Add area of operation component
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      area: {
        id: 0,
        name: '',
        radius: 0,
        state: '',
        store_uuid: ''
      }
    }
  },
  methods: {
    onSave () {
      this.$emit('input', this.area)
    }
  }
}
</script>
