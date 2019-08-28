import { mount, createLocalVue, shallowMount } from '@vue/test-utils'
import Vuetify from 'vuetify'
import Component from '~/components/financials/SalesChart.vue'
import { FIXTURE_SALES } from 'tests/__data__/sales'

describe('financials/SalesChart', () => {
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      localVue = createLocalVue()
      localVue.use(Vuetify)
    })
    test('sales set', () => {
      const component = mount(Component, {
        propsData: {
          sales: FIXTURE_SALES
        },
        localVue,
        stubs: {
          Lines: true
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
          sales: FIXTURE_SALES
        },
        stubs: {
          Lines: true
        }
      })
      expect(wrapper.vm.values).toEqual([4, 10, 20, 12, 32, 7, 7])
    })
    test('labels', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          sales: FIXTURE_SALES
        },
        stubs: {
          Lines: true
        }
      })
      expect(wrapper.vm.labels).toEqual(['Mar 01', 'Mar 02', 'Mar 03', 'Mar 04', 'Mar 05', 'Mar 06', 'Mar 07'])
    })
    test('chartData', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          sales: FIXTURE_SALES
        },
        stubs: {
          Lines: true
        }
      })
      expect(wrapper.vm.chartData).toEqual({
        labels: ['Mar 01', 'Mar 02', 'Mar 03', 'Mar 04', 'Mar 05', 'Mar 06', 'Mar 07'],
        datasets: [
          {
            borderColor: '#15b6a9',
            fill: false,
            data: [4, 10, 20, 12, 32, 7, 7],
            pointRadius: 5
          }
        ]
      })
    })
  })
})
