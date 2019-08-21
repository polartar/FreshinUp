import { shallowMount } from '@vue/test-utils'
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
    test('snapshot', async () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/foodfleet/financial-reports', { params: { 'page[size]': 10, 'page[number]': 1 } })
        .reply(200, { data: FIXTURE_REPORTABLES })
        .onGet('api/foodfleet/financial-reports').reply(200, FIXTURE_REPORTABLES_RESPONSE)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
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
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('financialReports/getItems')
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })
})
