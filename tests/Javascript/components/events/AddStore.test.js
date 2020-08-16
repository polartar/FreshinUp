import { createLocalVue, shallowMount } from '@vue/test-utils'
import Component from '~/components/events/AddStore.vue'

import { FIXTURE_STORE, FIXTURE_STORES } from 'tests/__data__/stores'

describe('Add member (store) in event component', () => {
  let localVue
  describe('Snapshot', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
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

      wrapper.setProps({ members: FIXTURE_STORES })

      wrapper.setData({
        pagination: {
          rowsPerPage: 5,
          page: 1
        }
      })

      expect(wrapper.vm.pages).toBe(1)

      wrapper.setData({
        pagination: {
          rowsPerPage: 2,
          page: 1
        }
      })

      expect(wrapper.vm.pages).toBe(2)
    })

    test('filteredMembers', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_STORES })

      wrapper.setData({
        selectedState: '',
        selectedType: '',
        selectedTag: []
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(3)
      expect(wrapper.vm.filteredMembers).toEqual(FIXTURE_STORES)

      wrapper.setData({
        selectedState: '00aabd70-aa77-42de-87fd-96449bbd5439',
        selectedType: '',
        selectedTag: []
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(1)
      expect(wrapper.vm.filteredMembers).toEqual([FIXTURE_STORES[1]])

      wrapper.setData({
        selectedState: '',
        selectedType: 1,
        selectedTag: []
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(3)
      expect(wrapper.vm.filteredMembers).toEqual(FIXTURE_STORES)

      wrapper.setData({
        selectedState: '',
        selectedType: '',
        selectedTag: ['4']
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(1)
      expect(wrapper.vm.filteredMembers).toEqual([FIXTURE_STORES[2]])
    })

    test('totalItems', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_STORES })

      expect(wrapper.vm.totalItems).toBe(3)
    })

    test('locations', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_STORES })

      const expectedLocations = [
        {
          id: 0,
          uuid: '',
          name: 'All locations'
        },
        {
          id: 1,
          uuid: 'c4fd0928-b7eb-43ee-871e-e63bfbd0ae7a',
          name: 'Port Gerald'
        },
        {
          id: 2,
          uuid: '00aabd70-aa77-42de-87fd-96449bbd5439',
          name: 'Carminestad'
        },
        {
          id: 2,
          uuid: 'dfb4bd02-c298-4dd4-ab49-2c3f156f2894',
          name: 'New Charlenebury'
        }
      ]

      expect(wrapper.vm.locations).toEqual(expectedLocations)
    })

    test('types', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_STORES })

      const expectedTypes = [
        {
          id: 0,
          name: 'All types'
        },
        {
          id: 1,
          name: 'Mobil'
        }
      ]

      expect(wrapper.vm.types).toEqual(expectedTypes)
    })

    test('tags', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_STORES })

      expect(wrapper.vm.tags).toHaveLength(4)
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('Toggle show filter', () => {
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

    test('Clear all filters', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        selectedState: 'aaa',
        selectedType: 'ttt',
        selectedTag: ['a', 'b']
      })

      wrapper.vm.clearAllFilters()
      expect(wrapper.vm.selectedState).toBe('')
      expect(wrapper.vm.selectedType).toBe('')
      expect(wrapper.vm.selectedTag).toEqual([])
    })

    test('Manage button label', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      expect(wrapper.vm.manageButtonLabel(FIXTURE_STORE)).toBe('Declined')
    })

    test('Manage button class', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      const expectedClassObject = {
        'blue-grey lighten-3 white--text': true,
        'primary': false,
        'blue-grey lighten-5': false
      }

      expect(wrapper.vm.manageButtonClass(FIXTURE_STORE)).toEqual(expectedClassObject)
    })
  })
})
