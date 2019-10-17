import FormatRangeDate from '~/components/mixins/FormatRangeDate'
describe('Mixins FormatRangeDate', () => {
  describe('method', () => {
    test('formatRangeDate(start, end) returns null for no argument', () => {
      expect(FormatRangeDate.methods.formatRangeDate()).toBeNull()
    })
    test('formatRangeDate(start, end) formatted date with same year and time', () => {
      const start = '2019-04-21 17:23:00.000'
      const end = '2019-04-23 17:23:00.000'
      const range = FormatRangeDate.methods.formatRangeDate(start, end)
      expect(range).toEqual('Apr 21 - 23 / 2019 5:23 PM')
    })
    test('formatRangeDate(start, end) formatted date with same year and day', () => {
      const start = '2019-04-21 09:23:00.000'
      const end = '2019-04-21 17:23:00.000'
      const range = FormatRangeDate.methods.formatRangeDate(start, end)
      expect(range).toEqual('Apr 21 / 2019 9:23 AM - 5:23 PM')
    })
  })
})
