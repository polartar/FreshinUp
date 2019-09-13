import { mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { createStore } from 'fresh-bus/store'
import find from 'lodash/find'
import Component from '~/pages/admin/contractor/check/index.vue'
import squares from '~/store/modules/squares'

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
    it('the code is correct', (done) => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onPost('api/foodfleet/squares').reply(201, [])
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({
      }, {
        modules: {
          squares: squares({})
        }
      })
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
        const request = find(mock.history.post, (req) => req.url === 'api/foodfleet/squares')
        expect(request.data).toBe('{"code":"sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw"}')
        expect(wrapper.vm.result).toBe(true)
        done()
      })
    })
    it('the code is not correct', (done) => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onPost('api/foodfleet/squares').reply(400, [])
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore({
      }, {
        modules: {
          squares: squares({})
        }
      })
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
        const request = find(mock.history.post, (req) => req.url === 'api/foodfleet/squares')
        expect(request.data).toBe('{"code":"sandbox-sq0idb-XpOEfx_tSDS3oIDPuf9Tqw"}')
        expect(wrapper.vm.result).toBe(false)
        done()
      })
    })
  })
})
