import { lazyLoad } from './utils'

describe('utils', () => {
  describe('lazyLoad', () => {
    test('when type is invalid', async () => {
      try {
        await lazyLoad({
          type: 'some weird type'
        })
      } catch (error) {
        expect(error.message).toBeDefined()
      }
    })
    test('when empty', async () => {
      try {
        await lazyLoad({})
      } catch (error) {
        expect(error.message).toEqual('options.type is required')
      }
    })
  })
})
