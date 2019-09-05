import { mount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/pages/admin/financials/transactions/index.vue'
import devices from '~/store/modules/devices'
import paymentTypes from '~/store/modules/paymentTypes'
import FormatMoney from 'fresh-bus/components/mixins/FormatMoney'

describe('Admin Financial Results Page', () => {
  let localVue, mock
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

      mock.onGet('api/foodfleet/payment-types')
        .reply(200, {})

      mock.onAny().reply(config => {
        console.warn('No mock match for ' + config.url, config)
        return [404, { message: 'No mock match for ' + config.url, data: config }]
      })

      const store = createStore({}, {
        modules: {
          devices: devices({}),
          paymentTypes: paymentTypes({})
        }
      })

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
        expect(wrapper.html()).toContain(FormatMoney.methods.formatMoney(wrapper.vm.gross, { format: '$0,0' }))
        expect(wrapper.html()).toContain(FormatMoney.methods.formatMoney(wrapper.vm.net, { format: '$0,0' }))
        expect(wrapper.html()).toContain(FormatMoney.methods.formatMoney(wrapper.vm.avgTicket, { format: '$0,0' }))
        // https://github.com/FreshinUp/foodfleet/issues/56
        // expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })
})
