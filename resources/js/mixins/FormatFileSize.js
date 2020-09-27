export default {
  methods: {
    formatFileSize (size) {
      // fallback value for non number
      if (!Number.isFinite(size) || isNaN(parseFloat(size))) {
        return size
      }
      const unit = 1024
      const kilo = 1 * unit
      const mega = kilo * unit
      const giga = mega * unit
      const tera = giga * unit
      const strategies = [{
        size: 1,
        unit: 'B'
      },
      {
        size: kilo,
        unit: 'KB'
      },
      {
        size: mega,
        unit: 'MB'
      },
      {
        size: giga,
        unit: 'GB'
      },
      {
        size: tera,
        unit: 'TB'
      }]
      for (let i = 0; i < strategies.length - 1; i++) {
        const strategy = strategies[i]
        if (size < strategies[i + 1].size) {
          return `${Math.round((size / strategy.size) * 100) / 100} ${strategy.unit}`
        }
      }
      return size
    }
  }
}
