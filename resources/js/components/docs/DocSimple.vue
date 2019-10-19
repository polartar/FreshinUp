<script>
import Simple from 'fresh-bus/components/search/simple'

export default {
  extends: Simple,
  props: {
    formatItems: {
      type: Function,
      default: (list) => {
        return list
      }
    }
  },
  computed: {
    items () {
      if (!this.results) {
        return []
      }
      let results = this.results
      if (this.formatItems instanceof Function) {
        results = this.formatItems(results)
      }
      return results.map(result => {
        const label = result[this.resultsTextKey].length > this.descriptionLimit
          ? result[this.resultsTextKey].slice(0, this.descriptionLimit) + '...'
          : result[this.resultsTextKey]
        return Object.assign({}, result, { label })
      })
    }
  }
}

</script>
