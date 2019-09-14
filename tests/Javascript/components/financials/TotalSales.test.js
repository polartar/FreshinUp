import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Vuetify from 'vuetify'
import Component from '~/components/financials/TotalSales.vue'
import { FIXTURE_PAYMENT_TYPE_TOTALS } from 'tests/__data__/paymentTypesTotal'

describe('financials/TotalSales', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('required props set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          gross: 20000,
          net: 10000,
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Visual', () => {
    beforeEach(() => {
      localVue = createLocalVue()
      localVue.use(Vuetify)
    })
    test('gross, net, cash and credit displayed with currency format', () => {
      const component = mount(Component, {
        propsData: {
          gross: 20000,
          net: 10000,
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        },
        localVue
      })
      // gross
      expect(component.text()).toContain('$200.00')
      // net
      expect(component.text()).toContain('$100.00')
      // cash
      expect(component.text()).toContain('$200.00')
      // credit
      expect(component.text()).toContain('$1,050.00')
    })
  })
  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('cash with set array', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          gross: 20000,
          net: 10000,
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        }
      })
      expect(wrapper.vm.cash).toBe(20000)
    })
    test('cash with empty array', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          gross: 20000,
          net: 10000,
          paymentTypeTotals: []
        }
      })
      expect(wrapper.vm.cash).toBe(0)
    })
    test('credit with set array', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          gross: 20000,
          net: 10000,
          paymentTypeTotals: FIXTURE_PAYMENT_TYPE_TOTALS
        }
      })
      expect(wrapper.vm.credit).toBe(105000)
    })
    test('credit with empty array', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          gross: 20000,
          net: 10000,
          paymentTypeTotals: []
        }
      })
      expect(wrapper.vm.credit).toBe(0)
    })
  })
})
