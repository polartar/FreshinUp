<script>
// TODO: should be replace with @freshinup/core-ui/src/components/FBtnStatus on version >= 1.54.0
import FBtnStatus from 'fresh-bus/components/ui/FBtnStatus'

const LEAD = 1
const PENDING_INVITATION = 2
const PENDING_REVIEW = 3
const APPROVED = 4
const ON_HOLD = 5
const COLORS = {
  [LEAD]: 'grey',
  [PENDING_INVITATION]: 'warning',
  [PENDING_REVIEW]: 'warning',
  [APPROVED]: 'success',
  [ON_HOLD]: 'error'
}
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
        color: COLORS[option.id] ? COLORS[option.id] : 'grey'
      }))
    }
  }
}
</script>
