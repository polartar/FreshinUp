import { mount, shallowMount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/pages/admin/financials/transactions/index.vue'
import { FIXTURE_FINANCIAL_SUMMARY } from 'tests/__data__/financialSummary'
import { FIXTURE_TRANSACTIONS } from 'tests/__data__/transactions'
import { FIXTURE_CURRENT_USER } from 'tests/__data__/user'
import { FIXTURE_FINANCIAL_MODIFIERS } from 'tests/__data__/modifiers'
import { FIXTURE_REPORTABLES } from 'tests/__data__/reportables'
import createStore from 'tests/createStore'

describe('Admin Financial Results Page', () => {
  let localVue, mock, store
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true, router: true })
      localVue = vue.localVue
      mock = vue.mock
    })
    afterEach(() => {
      mock.restore()
    })
    test('snapshot', done => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock

      mock.onGet('api/foodfleet/devices')
        .reply(200, {})

      mock.onGet('api/currentUser')
        .reply(200, FIXTURE_CURRENT_USER)

      mock.onGet('api/foodfleet/payment-types')
        .reply(200, {})

      mock.onGet('api/foodfleet/financial-modifiers')
        .reply(200, FIXTURE_FINANCIAL_MODIFIERS)

      mock.onGet('api/foodfleet/financial-summary')
        .reply(200, FIXTURE_FINANCIAL_SUMMARY)

      mock.onGet('api/foodfleet/transactions')
        .reply(200, { data: FIXTURE_TRANSACTIONS })

      mock.onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, { message: 'No mock match for ' + config.url, data: config }]
      })

      const store = createStore()

      const wrapper = mount(Component, {
        localVue: localVue,
        store,
        stubs: {
          Doughnut: true,
          Lines: true
        },
        mocks: {
          $route: {
            query: {}
          }
        }
      })

      // Action: load the page data
      Component.beforeRouteEnterOrUpdate(wrapper.vm, null, null, async () => {
        await wrapper.vm.$nextTick()
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock

      mock.onGet('api/foodfleet/devices')
        .reply(200, {})

      mock.onGet('api/currentUser')
        .reply(200, FIXTURE_CURRENT_USER)

      mock.onPut('api/users')
        .reply(201, FIXTURE_CURRENT_USER)

      mock.onGet('api/foodfleet/payment-types')
        .reply(200, {})

      mock.onGet('api/foodfleet/financial-modifiers')
        .reply(200, FIXTURE_FINANCIAL_MODIFIERS)

      mock.onGet('api/foodfleet/financial-summary')
        .reply(200, FIXTURE_FINANCIAL_SUMMARY)

      mock.onGet('api/foodfleet/transactions')
        .reply(200, { data: FIXTURE_TRANSACTIONS })

      mock.onPost('api/foodfleet/financial-reports')
        .reply(201, FIXTURE_REPORTABLES[0])

      mock.onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, { message: 'No mock match for ' + config.url, data: config }]
      })

      store = createStore()
    })
    afterEach(() => {
      mock.restore()
    })
    test('onPaginate()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store,
        stubs: {
          Doughnut: true,
          Lines: true
        },
        mocks: {
          $route: {
            query: {}
          }
        }
      })
      // Action: change State Machine's state
      await wrapper.vm.onPaginate({ rowsPerPage: 5, page: 2 })
      let request = mock.history.get[mock.history.get.length - 1]
      expect(request.params['page[number]']).toEqual(2)
      expect(request.params['page[size]']).toEqual(5)
    })
    test('onSaveSearch()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store,
        stubs: {
          Doughnut: true,
          Lines: true
        },
        mocks: {
          $route: {
            query: {}
          }
        }
      })
      // Action: change State Machine's state
      await wrapper.vm.onSaveSearch({
        name: 'Test',
        modifier_1_id: 1,
        modifier_2_id: 2
      })
      await wrapper.vm.$nextTick()
      let request = mock.history.post[0]
      expect(request.data).toEqual('{"name":"Test","modifier_1_id":1,"modifier_2_id":2,"filters":"{}"}')
    })
    test('saveParameters()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store,
        stubs: {
          Doughnut: true,
          Lines: true
        },
        mocks: {
          $route: {
            query: {}
          }
        }
      })
      // Action: change State Machine's state
      await wrapper.vm.saveParameters([
        'parameter_1',
        'parameter_1'
      ])
      await wrapper.vm.$nextTick()
      let request = mock.history.put[0]
      expect(request.data).toEqual('{"data_visibility":["parameter_1","parameter_1"]}')
    })
  })
})
