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
    test('changeDate() set values to date_after and date_before filters', () => {
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
      wrapper.setData({ range: {
        start: '2019-12-11',
        end: '2019-12-17'
      } })
      wrapper.vm.changeDate()
      expect(wrapper.props().filters.date_after).toBe('2019-12-11')
      expect(wrapper.props().filters.date_before).toBe('2019-12-17')
    })
  })
  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('searchLink', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          filters: {
            event_uuid: 1,
            company_uuid: 2,
            truck_uuid: 3,
            customer_uuid: 4,
            date_after: '2019-12-11',
            date_before: '2019-12-17'
          }
        }
      })
      expect(wrapper.vm.searchLink).toBe('/admin/financials/transactions?event_uuid=1&company_uuid=2&truck_uuid=3&customer_uuid=4&date_after=2019-12-11&date_before=2019-12-17')
    })
  })
})
