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
    const color = ['grey', 'warning', 'success', 'secondary', 'grey']
    return {
      items: this.options.map(item => {
        return {
          id: item.id,
          label: item.name,
          color: color[item.id - 1]
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
