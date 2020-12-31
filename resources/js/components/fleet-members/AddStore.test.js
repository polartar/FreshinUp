import { mount, shallowMount } from '@vue/test-utils'

import * as Stories from '~/components/fleet-members/AddStore.stories'
import { FIXTURE_STORES } from 'tests/__data__/stores'
import Component, { HEADERS, MULTIPLE_ITEM_ACTIONS } from './AddStore'
import { FIXTURE_EVENT } from '../../../../tests/Javascript/__data__/event'
import { FIXTURE_STORE_TYPES } from '../../../../tests/Javascript/__data__/storeTypes'

describe('components/fleet-members/AddStore', () => {
  describe('Snapshots', () => {
    test('Basic', async () => {
      const wrapper = mount(Stories.Basic())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Prop & Computed', () => {
    test('headers', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.headers).toMatchObject(HEADERS)

      const headers = [
        { text: 'Fleet member', value: 'name' }
      ]
      wrapper.setProps({
        headers
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.headers).toMatchObject(headers)
    })
    test('multipleItemActions', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.multipleItemActions).toMatchObject(MULTIPLE_ITEM_ACTIONS)

      const multipleItemActions = [
        { action: 'delete', view: 'Delete' }
      ]
      wrapper.setProps({
        multipleItemActions
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.multipleItemActions).toMatchObject(multipleItemActions)
    })
    test('event', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.event).toMatchObject({})

      const event = FIXTURE_EVENT
      wrapper.setProps({
        event
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.event).toMatchObject(event)
    })
    test('stores', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.stores).toMatchObject([])

      const stores = FIXTURE_STORES
      wrapper.setProps({
        stores
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.stores).toMatchObject(stores)
    })
    test('types', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.types).toMatchObject([])

      const types = FIXTURE_STORE_TYPES
      wrapper.setProps({
        types
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.types).toMatchObject(types)
    })
  })

  describe('Methods', () => {

    test('runFilters(payload)', () => {
      const wrapper = shallowMount(Component)
      const filters = {
        name: 'Magnolia',
      }
      wrapper.vm.runFilters(filters)
      const emitted = wrapper.emitted()['run-filter']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(filters)
    })

    test('viewItem(item)', () => {
      const wrapper = shallowMount(Component)
      const item = FIXTURE_STORES[0]
      wrapper.vm.runFilters(item)
      const emitted = wrapper.emitted()['run-filter']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toMatchObject(item)
    })

    test('hasBookedAnEvent(store)', () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          event: FIXTURE_STORES[0].event_stores[0]
        }
      })

      expect(wrapper.vm.hasBookedAnEvent(FIXTURE_STORES[3])).toBeTruthy()
      expect(wrapper.vm.hasBookedAnEvent(FIXTURE_STORES[2])).toBeFalsy()
    })

    test('isEligible(store)', () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        event: FIXTURE_STORES[0].event_stores[0]
      })

      expect(wrapper.vm.isEligible(FIXTURE_STORES[0])).toBeFalsy()
      expect(wrapper.vm.isEligible(FIXTURE_STORES[1])).toBeTruthy()
      expect(wrapper.vm.isEligible(FIXTURE_STORES[2])).toBeFalsy()
    })

    test('isAssignedToThisEvent(store)', () => {
      const wrapper = shallowMount(Component)

      wrapper.setProps({
        event: FIXTURE_STORES[0].event_stores[0]
      })

      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_STORES[0])).toBeTruthy()
      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_STORES[4])).toBeTruthy()
      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_STORES[1])).toBeFalsy()
    })

    test('isDeclined(store)', () => {
      const wrapper = shallowMount(Component)

      wrapper.setProps({
        event: FIXTURE_STORES[4].event_stores[0]
      })

      expect(wrapper.vm.isDeclined(FIXTURE_STORES[4])).toBeFalsy()
      expect(wrapper.vm.isDeclined(FIXTURE_STORES[1])).toBeFalsy()
    })

    test('manageButtonLabel(store)', () => {
      const wrapper = shallowMount(Component)

      wrapper.setProps({
        event: FIXTURE_STORES[0].event_stores[0]
      })

      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[1])).toBe('Assign')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[2])).toBe('Expired')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[3])).toBe('Booked')
      // expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[4])).toBe('Declined')
    })

    test('manageButtonClass(store)', () => {
      const wrapper = shallowMount(Component)

      wrapper.setProps({
        event: FIXTURE_STORES[4].event_stores[0]
      })

      const expectedClassObject = {
        'blue-grey lighten-3 white--text': false,
        'primary': false,
        'blue-grey lighten-5': true
      }

      expect(wrapper.vm.manageButtonClass(FIXTURE_STORES[4])).toMatchObject(expectedClassObject)
    })
  })
})
