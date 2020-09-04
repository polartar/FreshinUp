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
      required: true,
      default: () => ([])
    }
  },
  computed: {
    items () {
      return this.options.map(option => ({
        label: option.name,
        id: option.id,
        color: 'warning'
      }))
    }
  }
}
</script>
