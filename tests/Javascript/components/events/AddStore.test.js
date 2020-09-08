import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import Component from '~/components/events/AddStore.vue'

import * as Stories from '~/components/events/AddStore.stories'
import { FIXTURE_STORES } from '../../__data__/stores'
import { FIXTURE_STORE_TYPES } from '../../__data__/storeTypes'

describe('Add member (store) in event component', () => {
  let localVue
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('pages', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.setProps({ stores: FIXTURE_STORES })
      wrapper.setData({
        pagination: {
          rowsPerPage: 5,
          page: 1
        }
      })
      expect(wrapper.vm.pages).toBe(1)

      wrapper.setData({
        pagination: {
          rowsPerPage: 3,
          page: 1
        }
      })
      expect(wrapper.vm.pages).toBe(2)
    })

    test('filteredStores', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ stores: FIXTURE_STORES })
      wrapper.setData({
        selectedState: '',
        selectedType: '',
        selectedTags: []
      })

      expect(wrapper.vm.filteredStores).toHaveLength(5)
      expect(wrapper.vm.filteredStores).toEqual(FIXTURE_STORES)

      wrapper.setData({
        selectedState: 'New York',
        selectedType: '',
        selectedTags: []
      })

      expect(wrapper.vm.filteredStores).toHaveLength(1)
      expect(wrapper.vm.filteredStores).toEqual([FIXTURE_STORES[1]])

      wrapper.setData({
        selectedState: '',
        selectedType: 2,
        selectedTags: []
      })

      expect(wrapper.vm.filteredStores).toHaveLength(4)
      expect(wrapper.vm.filteredStores).toEqual(FIXTURE_STORES.filter(s => s.type_id === 2))

      wrapper.setData({
        selectedState: '',
        selectedType: '',
        selectedTags: [FIXTURE_STORES[0].tags[0]]
      })

      expect(wrapper.vm.filteredStores).toHaveLength(3)
    })

    test('totalItems', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ stores: FIXTURE_STORES })

      expect(wrapper.vm.totalItems).toBe(5)
    })

    test('locations', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ stores: FIXTURE_STORES })

      const expectedLocations = ['California', 'New York']

      expect(wrapper.vm.locations).toEqual(expectedLocations)
    })

    test('tags', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ stores: FIXTURE_STORES })
      expect(wrapper.vm.tags).toHaveLength(2)
    })

    test('storeTypesById', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        storeTypes: FIXTURE_STORE_TYPES
      })
      await wrapper.vm.$nextTick()
      const expected = FIXTURE_STORE_TYPES.reduce((map, type) => {
        map[type.id] = type
        return map
      }, {})
      expect(wrapper.vm.storeTypesById).toMatchObject(expected)
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('showFilters()', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        showFilters: false
      })

      expect(wrapper.vm.showFilters).toBeFalsy()

      wrapper.vm.toggleShowFilter()
      expect(wrapper.vm.showFilters).toBeTruthy()

      wrapper.vm.toggleShowFilter()
      expect(wrapper.vm.showFilters).toBeFalsy()
    })

    test('clearAllFilters()', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        selectedState: 'aaa',
        selectedType: 'ttt',
        selectedTags: ['a', 'b']
      })

      wrapper.vm.clearAllFilters()
      expect(wrapper.vm.selectedState).toBe('')
      expect(wrapper.vm.selectedType).toBe('')
      expect(wrapper.vm.selectedTags).toEqual([])
    })

    test('hasBookedAnEvent(store)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          event: FIXTURE_STORES[0].events[0]
        }
      })

      expect(wrapper.vm.hasBookedAnEvent(FIXTURE_STORES[3])).toBeTruthy()
      expect(wrapper.vm.hasBookedAnEvent(FIXTURE_STORES[2])).toBeFalsy()
    })

    test('isEligible(store)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.setProps({
        event: FIXTURE_STORES[0].events[0]
      })

      expect(wrapper.vm.isEligible(FIXTURE_STORES[0])).toBeTruthy()
      expect(wrapper.vm.isEligible(FIXTURE_STORES[1])).toBeTruthy()
      expect(wrapper.vm.isEligible(FIXTURE_STORES[2])).toBeFalsy()
    })

    test('isAssignedToThisEvent(store)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_STORES[0].events[0]
      })

      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_STORES[0])).toBeTruthy()
      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_STORES[4])).toBeTruthy()
      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_STORES[1])).toBeFalsy()
    })

    test('isDeclined(store)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_STORES[4].events[0]
      })

      expect(wrapper.vm.isDeclined(FIXTURE_STORES[4])).toBeTruthy()
      expect(wrapper.vm.isDeclined(FIXTURE_STORES[1])).toBeFalsy()
    })

    test('manageButtonLabel(store)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_STORES[0].events[0]
      })

      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[0])).toBe('Assigned')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[1])).toBe('Assign')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[2])).toBe('Expired')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[3])).toBe('Booked')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORES[4])).toBe('Declined')
    })

    test('manageButtonClass(store)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_STORES[4].events[0]
      })

      const expectedClassObject = {
        'blue-grey lighten-3 white--text': true,
        'primary': false,
        'blue-grey lighten-5': false
      }

      expect(wrapper.vm.manageButtonClass(FIXTURE_STORES[4])).toMatchObject(expectedClassObject)
    })
  })
})
