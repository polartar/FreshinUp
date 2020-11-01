import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import find from 'lodash/find'
import Page from './index.vue'
import createStore from 'tests/createStore'
import { mount } from 'vue-cli-plugin-freshinup-ui/utils/testing'
describe('pages/admin/contractor/check', () => {
  let localVue, mock, store
  describe('Scenarios', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      store = createStore()
    })
    afterEach(() => {
      mock.restore()
    })
    test.skip('when the code is correct', (done) => {
      mock.onPost(/.*foodfleet\/squares\/authorize.*/)
        .reply(200, {})

      const wrapper = mount(Page, {
        localVue,
        store,
        mocks: {
          $route: {
            query: {
              code: 'sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw'
            }
          }
        }
      })

      Page.beforeRouteEnterOrUpdate(wrapper.vm, null, null, () => {
        const request = find(mock.history.post, (req) => req.url === 'api/foodfleet/squares/authorize')
        expect(request.data).toBe('{"code":"sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw"}')
        expect(wrapper.vm.result).toBe(true)
        done()
      })
    })
    test.skip('when the code is not correct', (done) => {
      mock.onPost(/.*foodfleet\/squares\/authorize.*/)
        .reply(200, { result: false })

      const wrapper = mount(Page, {
        localVue: localVue,
        store,
        mocks: {
          $route: {
            query: {
              code: 'sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw'
            }
          }
        }
      })

      Page.beforeRouteEnterOrUpdate(wrapper.vm, null, null, () => {
        const request = find(mock.history.post, (req) => req.url === 'api/foodfleet/squares/authorize')
        expect(request.data).toBe('{"code":"sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw"}')
        expect(wrapper.vm.result).toBe(false)
        done()
      })
    })
  })
})
