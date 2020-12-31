import { shallowMount, mount } from '@vue/test-utils'
import Component, { HEADERS, ITEM_ACTIONS, MULTIPLE_ITEM_ACTIONS } from '~/components/events/StoreList.vue'
import * as Stories from '~/components/events/StoreList.stories'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import { FIXTURE_STORES } from 'tests/__data__/stores'

describe('components/events/StoreList', () => {
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
    test('stores', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.stores).toMatchObject([])

      wrapper.setProps({
        stores: FIXTURE_STORES
      })
      expect(wrapper.vm.stores).toMatchObject(FIXTURE_STORES)
    })
    test('statuses', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toMatchObject([])

      wrapper.setProps({
        statuses: FIXTURE_STORE_STATUSES
      })
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_STORE_STATUSES)
    })
    test('headers', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.headers).toMatchObject(HEADERS)

      const headers = [
        { text: 'STATUS', sortable: true, value: 'status_id', align: 'left' }
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
    test('viewItem(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_STORES[0]
      wrapper.vm.viewItem(item)
      expect(wrapper.emitted()['manage-view']).toBeTruthy()
      expect(wrapper.emitted()['manage-view'][0][0]).toMatchObject(FIXTURE_STORES[0])
    })
  })
})
