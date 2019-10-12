import moment from 'moment'
export default {
  methods: {
    formatRangeDate (startDate, endDate) {
      const formatArr = [ 'MMM', 'DD', '/ YYYY', 'h:mm A' ]
      if (!startDate || !endDate) return null
      return formatArr.map(format => {
        const startFormat = moment(startDate).format(format)
        const endFormat = moment(endDate).format(format)
        return startFormat === endFormat ? startFormat : startFormat + ' - ' + endFormat
      }).join(' ')
    }
  }
}
