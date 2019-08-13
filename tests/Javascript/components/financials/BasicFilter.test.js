import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/financials/BasicFilter.vue'

describe('BasicFilter', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('select functions change filters', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          filters: {
            event_uuid: null,
            company_uuid: null,
            truck_uuid: null,
            customer_uuid: null,
            date_after: null,
            date_before: null
          }
        }
      })
      wrapper.vm.selectEvent({ uuid: 1 })
      expect(wrapper.props().filters.event_uuid).toBe(1)
      wrapper.vm.selectCompany({ uuid: 2 })
      expect(wrapper.props().filters.company_uuid).toBe(2)
      wrapper.vm.selectTruck({ uuid: 3 })
      expect(wrapper.props().filters.fleet_member_uuid).toBe(3)
      wrapper.vm.selectCustomer({ uuid: 4 })
      expect(wrapper.props().filters.contractor_uuid).toBe(4)
    })
  })
})
