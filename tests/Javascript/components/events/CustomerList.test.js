import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/CustomerList'
import { FIXTURE_CUSTOMERS } from 'tests/__data__/customers'
import { FIXTURE_CUSTOMER_STATUSES } from 'tests/__data__/customerStatuses'

describe('Customer list component', () => {
  let localVue

  test('Snapshots', () => {
    localVue = createLocalVue()
    const wrapper = mount(Component, {
      localVue: localVue,
      propsData: {
        statuses: FIXTURE_CUSTOMER_STATUSES,
        customers: FIXTURE_CUSTOMERS
      }
    })
    expect(wrapper.element).toMatchSnapshot()
  })

  test('Customer list is empty', () => {
    localVue = createLocalVue()
    const wrapper = mount(Component, {
      localVue: localVue,
      propsData: {
        statuses: FIXTURE_CUSTOMER_STATUSES,
        customers: []
      }
    })
    expect(wrapper.element).toMatchSnapshot()
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('changeStatus function emitted change-status action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockCustomer = {
        uuid: '6c3a3242-7158-408c-a7c7-8051100db64f', status: 1
      }

      wrapper.vm.changeStatus(2, mockCustomer)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('viewDetails function emitted view-details action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.viewDetails('view-details-url')

      expect(wrapper.emitted()['view-details']).toBeTruthy()
      expect(wrapper.emitted()['view-details'][0][0]).toEqual('view-details-url')
    })
  })
})
