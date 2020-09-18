import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import Component, { DEFAULT_FILTERS } from '~/components/venues/FilterSorter.vue'
import * as Stories from '~/components/venues/FilterSorter.stories'
import { FIXTURE_USERS } from '../../__data__/users'
import { FIXTURE_VENUE_STATUSES } from '../../__data__/venues'
import { FIXTURE_USER } from '../../__data__/user'

describe('components/venues/FilterSorter', () => {
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithStatus', () => {
      const wrapper = mount(Stories.WithStatus())
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Props & Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('filters', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.filters).toMatchObject(DEFAULT_FILTERS)

      const filters = {
        status_id: [1, 2],
        owner_uuid: FIXTURE_USERS[0].uuid
      }
      wrapper.setProps({
        filters
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.filters).toMatchObject(filters)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_VENUE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_VENUE_STATUSES)
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('clearFilters()', async () => {
      const clearTerm = jest.fn()
      const wrapper = shallowMount(Component, {
        stubs: {
          'f-autocomplete': true,
          'multi-simple': true
        },
        propsData: {
          statuses: FIXTURE_VENUE_STATUSES,
          filters: {
            status_id: [1, 2],
            owner_uuid: FIXTURE_USERS[0].uuid
          }
        }
      })
      expect(wrapper.vm.filters.status_id).not.toBeNull()
      expect(wrapper.vm.filters.owner_uuid).not.toBeNull()
      wrapper.vm.$refs = {
        owner: {
          clearTerm
        }
      }
      wrapper.vm.clearFilters()
      await wrapper.vm.$nextTick()
      expect(clearTerm).toHaveBeenCalled()
      expect(wrapper.vm.filters.status_id).toBeNull()
      expect(wrapper.vm.filters.owner_uuid).toBeNull()
    })
    test('run(params)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      const filters = {
        status_id: [ 1, 2 ],
        owner_uuid: 'o110'
      }
      wrapper.setProps({
        statuses: FIXTURE_VENUE_STATUSES,
        filters
      })
      wrapper.vm.run({})
      const emitted = wrapper.emitted().runFilter
      expect(emitted).toBeTruthy()
      const runParams = emitted[0][0]
      expect(runParams['status_id']).toEqual(filters.status_id)
      expect(runParams['owner_uuid']).toEqual(filters.owner_uuid)
    })
    test('selectOwner(user)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.filters.owner_uuid).toBeNull()
      const user = FIXTURE_USER
      wrapper.vm.selectOwner(user)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.filters.owner_uuid).toEqual(user.uuid)
    })
  })
})
