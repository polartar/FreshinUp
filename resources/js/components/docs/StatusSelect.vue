<script>
import FBtnStatus from 'fresh-bus/components/ui/FBtnStatus'
export default {
  extends: FBtnStatus,
  props: {
    value: {
      type: Number,
      default: 1
    },
    options: {
      type: Array,
      required: true
    }
  },
  computed: {
    items () {
      // Why don't we just use FBtnStatus' ability to change the "options" data via the props
      return this.options.map(item => {
        return {
          id: item.value,
          label: item.text,
          color: item.color
        }
      })
    }
  }
}
</script>
