<script>
// TODO: should be replace with @freshinup/core-ui/src/components/FBtnStatus on version >= 1.54.0
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
      return this.options
    }
  }
}
</script>
