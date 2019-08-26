import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_REPORTABLES, FIXTURE_REPORTABLES_RESPONSE } from 'tests/__data__/reportables'
import Component from '~/pages/admin/financials/index.vue'
import devices from '~/store/modules/devices'
import paymentTypes from '~/store/modules/paymentTypes'
import financialReports from '~/store/modules/financialReports'

describe('Admin Financial Reports Page', () => {
  let localVue, mock
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
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
        .onGet('api/foodfleet/financial-reports', { params: { 'page[size]': 10, 'page[number]': 1 } })
        .reply(200, { data: FIXTURE_REPORTABLES })
        .onGet('api/foodfleet/financial-reports').reply(200, FIXTURE_REPORTABLES_RESPONSE)

      mock.onGet('api/foodfleet/devices')
        .reply(200, {})

      mock.onGet('api/foodfleet/payment-types')
        .reply(200, {})

      mock.onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, { message: 'No mock match for ' + config.url, data: config }]
      })

      const store = createStore({
        financialReports: {
          items: FIXTURE_REPORTABLES_RESPONSE
        }
      }, {
        modules: {
          devices: devices({}),
          paymentTypes: paymentTypes({}),
          financialReports: financialReports({})
        }
      })

      const wrapper = mount(Component, {
        localVue: localVue,
        store
      })

      // Action: load the page data
      Component.beforeRouteEnterOrUpdate(wrapper.vm, null, null, async () => {
        await wrapper.vm.$nextTick()
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })
})
