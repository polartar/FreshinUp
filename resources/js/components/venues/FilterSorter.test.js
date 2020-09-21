import { mount, shallowMount } from '@vue/test-utils'
import Component, { DEFAULT_FILTERS } from './FilterSorter.vue'
import * as Stories from './FilterSorter.stories'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import { FIXTURE_VENUE_STATUSES } from '../../../../tests/Javascript/__data__/venueStatuses'

describe('components/venues/FilterSorter', () => {
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Expanded', () => {
      const wrapper = mount(Stories.Expanded())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_VENUE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_VENUE_STATUSES)
    })
    test('filters', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.filters).toMatchObject(DEFAULT_FILTERS)

      const filters = {
        a: 1,
        b: 2
      }
      wrapper.setProps({
        filters
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.filters).toMatchObject(filters)
    })
  })

  describe('Methods', () => {
    describe('run(params)', () => {
      test('when empty', async () => {
        const wrapper = shallowMount(Component)
        const params = {}
        wrapper.vm.run(params)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted().runFilter
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject(params)
      })
      test('when set', async () => {
        const wrapper = shallowMount(Component)
        const params = {
          term: 'abc'
        }
        wrapper.vm.run(params)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted().runFilter
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject({
          name: params.term
        })
      })
    })
    test('selectOwner(user)', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.$refs = {
        filter: {
          getRunParams: jest.fn(() => ({}))
        }
      }
      expect(wrapper.vm.filters.owner_uuid).toBeNull()

      const user = FIXTURE_USERS[0]
      wrapper.vm.selectOwner(user)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.filters.owner_uuid).toEqual(user.uuid)
    })
    test('clearFilters()', async () => {
      const wrapper = shallowMount(Component)
      const clearTermMock = jest.fn()
      wrapper.vm.$refs = {
        filter: {
          getRunParams: jest.fn(() => ({}))
        },
        owner: {
          clearTerm: clearTermMock
        }
      }
      const filters = {
        status_id: [1, 2],
        owner_uuid: 'o111'
      }
      wrapper.setProps({
        filters
      })
      expect(wrapper.vm.filters.status_id).toEqual(filters.status_id)
      expect(wrapper.vm.filters.owner_uuid).toEqual(filters.owner_uuid)

      wrapper.vm.clearFilters()
      expect(clearTermMock).toHaveBeenCalled()
      expect(wrapper.vm.filters.status_id).toBeNull()
      expect(wrapper.vm.filters.owner_uuid).toBeNull()
    })
  })
})
