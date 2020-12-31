import { mount, shallowMount } from '@vue/test-utils'
import Component, { HEADERS, ITEM_ACTIONS, MULTIPLE_ITEM_ACTIONS } from '~/components/events/CustomerList'
import * as Stories from '~/components/events/CustomerList.stories'
import { FIXTURE_CUSTOMERS } from '../../../../tests/Javascript/__data__/customers'
import { FIXTURE_CUSTOMER_STATUSES } from '../../../../tests/Javascript/__data__/customerStatuses'

describe('components/events/CustomerList', () => {
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('customers', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.customers).toMatchObject([])

      wrapper.setProps({
        customers: FIXTURE_CUSTOMERS
      })
      expect(wrapper.vm.customers).toMatchObject(FIXTURE_CUSTOMERS)
    })
    test('statuses', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toMatchObject([])

      wrapper.setProps({
        statuses: FIXTURE_CUSTOMER_STATUSES
      })
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_CUSTOMER_STATUSES)
    })
    test('headers', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.headers).toMatchObject(HEADERS)

      const headers = [
        { text: 'CONTRACT STATUS', sortable: true, value: 'status', align: 'left' }
      ]
      wrapper.setProps({
        headers
      })
      expect(wrapper.vm.headers).toMatchObject(headers)
    })
    test('itemActions', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.itemActions).toMatchObject(ITEM_ACTIONS)

      const itemActions = [
        { action: 'edit', text: 'Edit' }
      ]
      wrapper.setProps({
        itemActions
      })
      expect(wrapper.vm.itemActions).toMatchObject(itemActions)
    })
    test('multipleItemActions', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.multipleItemActions).toMatchObject(MULTIPLE_ITEM_ACTIONS)

      const multipleItemActions = [
        { action: 'delete', text: 'Delete' }
      ]
      wrapper.setProps({
        multipleItemActions
      })
      expect(wrapper.vm.multipleItemActions).toMatchObject(multipleItemActions)
    })
  })

  describe('Methods', () => {
    test('changeStatus(value, item)', () => {
      const wrapper = shallowMount(Component)
      const mockCustomer = {
        uuid: '6c3a3242-7158-408c-a7c7-8051100db64f', status: 1
      }
      wrapper.vm.changeStatus(2, mockCustomer)
      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('viewItem(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_CUSTOMERS[0]
      wrapper.vm.viewItem(item)
      const emitted = wrapper.emitted()
      expect(emitted['manage-view']).toBeTruthy()
      expect(emitted['manage-view'][0][0]).toMatchObject(item)
    })
  })
})
