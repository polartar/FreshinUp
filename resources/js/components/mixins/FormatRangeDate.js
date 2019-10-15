import FormatDate from 'fresh-bus/components/mixins/FormatDate'
export default {
  methods: {
    formatRangeDate (startDate, endDate) {
      const formatArr = [ 'MMM', 'DD', '/ YYYY', 'h:mm A' ]
      if (!startDate || !endDate) return null
      return formatArr.map(format => {
        const startFormat = FormatDate.methods.formatDate(startDate, format)
        const endFormat = FormatDate.methods.formatDate(endDate, format)
        return startFormat === endFormat ? startFormat : startFormat + ' - ' + endFormat
      }).join(' ')
    }
  }
}
