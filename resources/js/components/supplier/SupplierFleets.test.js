import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './SupplierFleets.stories'
import Component from './SupplierFleets.vue'
import { FIXTURE_STORE_STATUS_STATS } from 'tests/__data__/storeStatusStats'
import { FIXTURE_STORE_STATUSES } from '../../../../tests/Javascript/__data__/storeStatuses'
import { FIXTURE_STORES } from '../../../../tests/Javascript/__data__/stores'

describe('components/supplier/SupplierFleets', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('stores', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.stores).toHaveLength(0)

      wrapper.setProps({
        stores: FIXTURE_STORES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.stores).toMatchObject(FIXTURE_STORES)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_STORE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_STORE_STATUSES)
    })
    test('statusStats', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statusStats).toHaveLength(0)

      wrapper.setProps({
        statusStats: FIXTURE_STORE_STATUS_STATS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statusStats).toMatchObject(FIXTURE_STORE_STATUS_STATS)
    })
  })

  describe('Methods', () => {
    test('viewAll()', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.viewAll()
      const emitted = wrapper.emitted()['manage-multiple-view']
      expect(emitted).toBeTruthy()
    })
    test('viewItem(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_STORES[0]
      wrapper.vm.viewItem(item)
      const emitted = wrapper.emitted()['manage-view']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(item)
    })
    test('changeStatus(value, item)', () => {
      const wrapper = shallowMount(Component)
      const value = 1
      const item = FIXTURE_STORE_STATUSES[0]
      wrapper.vm.changeStatus(value, item)
      const emitted = wrapper.emitted()['change-status']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toEqual(value)
      expect(emitted[0][1]).toMatchObject(item)
    })
    describe('manageMultiple(action, items, value)', () => {
      const items = FIXTURE_STORES
      test('when action = view', () => {
        const wrapper = shallowMount(Component)
        wrapper.vm.manageMultiple('view', items)
        const emitted = wrapper.emitted()['manage-multiple']
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toEqual('view')
        expect(emitted[0][1]).toMatchObject(items)
      })

      test('when action = status', () => {
        const wrapper = shallowMount(Component)
        wrapper.vm.manageMultiple('status', items, 2)
        const emitted = wrapper.emitted()['manage-multiple']
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toEqual('status')
        expect(emitted[0][1]).toMatchObject(items)
        expect(emitted[0][2]).toEqual(2)
      })
    })
  })
})
