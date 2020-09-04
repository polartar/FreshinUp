import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import Component from '~/components/events/AddStore.vue'

import * as Stories from '~/components/events/AddStore.stories'
import { FIXTURE_FLEET_MEMBER_EVENT, FIXTURE_FLEET_MEMBERS } from '../../__data__/fleet-members'

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

      wrapper.setProps({ members: FIXTURE_FLEET_MEMBERS })

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

    test('filteredMembers', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_FLEET_MEMBERS })

      wrapper.setData({
        selectedState: '',
        selectedType: '',
        selectedTags: []
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(5)
      expect(wrapper.vm.filteredMembers).toEqual(FIXTURE_FLEET_MEMBERS)

      wrapper.setData({
        selectedState: 'New York',
        selectedType: '',
        selectedTags: []
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(1)
      expect(wrapper.vm.filteredMembers).toEqual([FIXTURE_FLEET_MEMBERS[1]])

      wrapper.setData({
        selectedState: '',
        selectedType: 2,
        selectedTags: []
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(5)
      expect(wrapper.vm.filteredMembers).toEqual(FIXTURE_FLEET_MEMBERS)

      wrapper.setData({
        selectedState: '',
        selectedType: '',
        selectedTags: ['ASIAN']
      })

      expect(wrapper.vm.filteredMembers).toHaveLength(3)
    })

    test('totalItems', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_FLEET_MEMBERS })

      expect(wrapper.vm.totalItems).toBe(5)
    })

    test('locations', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_FLEET_MEMBERS })

      const expectedLocations = ['California', 'New York']

      expect(wrapper.vm.locations).toEqual(expectedLocations)
    })

    test('tags', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({ members: FIXTURE_FLEET_MEMBERS })

      expect(wrapper.vm.tags).toHaveLength(2)
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
        selectedTags: ['a', 'b']
      })

      wrapper.vm.clearAllFilters()
      expect(wrapper.vm.selectedState).toBe('')
      expect(wrapper.vm.selectedType).toBe('')
      expect(wrapper.vm.selectedTags).toEqual([])
    })

    test('Member has booked an event', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_FLEET_MEMBER_EVENT
      })

      expect(wrapper.vm.hasBookedAnEvent(FIXTURE_FLEET_MEMBERS[3])).toBeTruthy()
      expect(wrapper.vm.hasBookedAnEvent(FIXTURE_FLEET_MEMBERS[2])).toBeFalsy()
    })

    test('Member is eligible', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_FLEET_MEMBER_EVENT
      })

      expect(wrapper.vm.isEligible(FIXTURE_FLEET_MEMBERS[0])).toBeTruthy()
      expect(wrapper.vm.isEligible(FIXTURE_FLEET_MEMBERS[1])).toBeTruthy()
      expect(wrapper.vm.isEligible(FIXTURE_FLEET_MEMBERS[2])).toBeFalsy()
    })

    test('Member is assigned to this event', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_FLEET_MEMBER_EVENT
      })

      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_FLEET_MEMBERS[0])).toBeTruthy()
      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_FLEET_MEMBERS[4])).toBeTruthy()
      expect(wrapper.vm.isAssignedToThisEvent(FIXTURE_FLEET_MEMBERS[1])).toBeFalsy()
    })

    test('Member has declined this event', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_FLEET_MEMBER_EVENT
      })

      expect(wrapper.vm.isDeclined(FIXTURE_FLEET_MEMBERS[4])).toBeTruthy()
      expect(wrapper.vm.isDeclined(FIXTURE_FLEET_MEMBERS[1])).toBeFalsy()
    })

    test('Manage button label', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_FLEET_MEMBER_EVENT
      })

      expect(wrapper.vm.manageButtonLabel(FIXTURE_FLEET_MEMBERS[0])).toBe('Assigned')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_FLEET_MEMBERS[1])).toBe('Assign')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_FLEET_MEMBERS[2])).toBe('Expired')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_FLEET_MEMBERS[3])).toBe('Booked')
      expect(wrapper.vm.manageButtonLabel(FIXTURE_FLEET_MEMBERS[4])).toBe('Declined')
    })

    test('Manage button class', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setProps({
        event: FIXTURE_FLEET_MEMBER_EVENT
      })

      const expectedClassObject = {
        'blue-grey lighten-3 white--text': true,
        'primary': false,
        'blue-grey lighten-5': false
      }

      expect(wrapper.vm.manageButtonClass(FIXTURE_FLEET_MEMBERS[4])).toEqual(expectedClassObject)
    })
  })
})
