import FormatDate from 'fresh-bus/components/mixins/FormatDate'
export default {
  methods: {
    formatRangeDate (startDate, endDate) {
      if (!startDate || !endDate) return null
      const judgeTime = this._judgeDate(startDate, endDate, 'h:mm A', 'h:mm A')

      const judgeYear = this._judgeDate(startDate, endDate, '/ YYYY', 'MMM DD / YYYY h:mm A')
      if (!judgeYear.equal) {
        return judgeYear.result
      }

      const judgeMonth = this._judgeDate(startDate, endDate, 'MMM', 'MMM DD')
      if (!judgeMonth.equal) {
        return judgeMonth.result + ' ' + judgeYear.result + ' ' + judgeTime.result
      }

      const judgeDay = this._judgeDate(startDate, endDate, 'DD', 'DD')

      return judgeMonth.result + ' ' + judgeDay.result + ' ' + judgeYear.result + ' ' + judgeTime.result
    },

    _judgeDate (startDate, endDate, sameFormat, diffFormat) {
      const startFormat = FormatDate.methods.formatDate(startDate, sameFormat)
      const endFormat = FormatDate.methods.formatDate(endDate, sameFormat)
      const startDiffFormat = FormatDate.methods.formatDate(startDate, diffFormat)
      const endDiffFormat = FormatDate.methods.formatDate(endDate, diffFormat)
      return {
        equal: startFormat === endFormat,
        result: startFormat === endFormat ? startFormat : startDiffFormat + ' - ' + endDiffFormat
      }
    }
  }
}
