import FormatFileSize from './FormatFileSize'

describe('mixins/FormatFileSize', () => {
  test('123 = 123 B', async () => {
    expect(FormatFileSize.methods.formatFileSize(123)).toEqual('123 B')
  })
  test('5644 = 5.51 KB', () => {
    expect(FormatFileSize.methods.formatFileSize(5644)).toEqual('5.51 KB')
  })
  test('2537253 = 2.42 MB', async () => {
    expect(FormatFileSize.methods.formatFileSize(2537253)).toEqual('2.42 MB')
  })
  test('564654654 = 538.5 MB', () => {
    expect(FormatFileSize.methods.formatFileSize(564654654)).toEqual('538.5 MB')
  })
  test('64654654444 = 60.21 GB', () => {
    expect(FormatFileSize.methods.formatFileSize(64654654444)).toEqual('60.21 GB')
  })
})
