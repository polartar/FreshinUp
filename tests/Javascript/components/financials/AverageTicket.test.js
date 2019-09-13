import { createLocalVue, mount } from '@vue/test-utils'
import Vuetify from 'vuetify'
import Component from '~/components/financials/AverageTicket.vue'

describe('financials/TotalSales', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('required props set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          averageTicket: 23470
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
    test('averageTicket displayed with currency format', () => {
      const component = mount(Component, {
        propsData: {
          averageTicket: 23470
        },
        localVue
      })
      expect(component.html()).toContain('$234.70')
    })
  })
})
