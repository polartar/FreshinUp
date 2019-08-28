import { mount, createLocalVue, shallowMount } from '@vue/test-utils'
import Vuetify from 'vuetify'
import Component from '~/components/financials/SalesByMethod.vue'
import { FIXTURE_PAYMENT_TYPE_TOTALS } from 'tests/__data__/paymentTypesTotal'

describe('financials/SalesByMethod', () => {
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      localVue = createLocalVue()
      localVue.use(Vuetify)
    })
    test('sales set', () => {
      const component = mount(Component, {
        propsData: {
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        localVue,
        stubs: {
          Doughnut: true
        }
      })
      expect(component.element).toMatchSnapshot()
    })
  })
  describe('Computed', () => {
    test('values', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        stubs: {
          Doughnut: true
        }
      })
      expect(wrapper.vm.values).toEqual([30000, 40000, 35000, 20000])
    })
    test('labels', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        stubs: {
          Doughnut: true
        }
      })
      expect(wrapper.vm.labels).toEqual(['VISA 24%', 'MASTERCARD 32%', 'AMEX 28%', 'CASH 16%'])
    })
    test('total', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        stubs: {
          Doughnut: true
        }
      })
      expect(wrapper.vm.total).toEqual(125000)
    })
    test('chartData', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        stubs: {
          Lines: true
        }
      })
      expect(wrapper.vm.chartData).toEqual({ 'datasets': [{ 'backgroundColor': ['#1976D2', '#FB8C00', '#424242', '#2196F3'], 'borderWidth': 0, 'data': wrapper.vm.values }], 'labels': wrapper.vm.labels })
    })
  })
  describe('Methods', () => {
    test('getPercentageString', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        stubs: {
          Doughnut: true
        }
      })
      expect(wrapper.vm.getPercentageString(20000)).toEqual('16%')
    })
  })
})
