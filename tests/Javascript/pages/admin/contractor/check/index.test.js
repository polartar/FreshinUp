import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import find from 'lodash/find'
import Component from '~/pages/admin/contractor/check/index.vue'
import createStore from 'tests/createStore'

describe('Contractor check authorization page', () => {
  let localVue, mock
  describe('Scenarios', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
    })
    afterEach(() => {
      mock.restore()
    })
    it.skip('the code is correct', (done) => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onPost('api/foodfleet/squares/authorize').reply(201, [])
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore()
      const wrapper = mount(Component, {
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

      Component.beforeRouteEnterOrUpdate(wrapper.vm, null, null, () => {
        const request = find(mock.history.post, (req) => req.url === 'api/foodfleet/squares/authorize')
        expect(request.data).toBe('{"code":"sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw"}')
        expect(wrapper.vm.result).toBe(true)
        done()
      })
    })
    it.skip('the code is not correct', (done) => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onPost('api/foodfleet/squares/authorize').reply(400, [])
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore()
      const wrapper = mount(Component, {
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

      Component.beforeRouteEnterOrUpdate(wrapper.vm, null, null, () => {
        const request = find(mock.history.post, (req) => req.url === 'api/foodfleet/squares/authorize')
        expect(request.data).toBe('{"code":"sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw"}')
        expect(wrapper.vm.result).toBe(false)
        done()
      })
    })
  })
})
