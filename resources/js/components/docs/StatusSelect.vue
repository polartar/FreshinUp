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
  data () {
    return {
      items: this.options.map(item => {
        return {
          id: item.value,
          label: item.text,
          color: item.color
        }
      })
    }
  },
  computed: {
    activeItem () {
      for (let i in this.items) {
        if (this.items[i].id === this.value) return this.items[i]
      }
      return null
    }
  }
}
</script>
