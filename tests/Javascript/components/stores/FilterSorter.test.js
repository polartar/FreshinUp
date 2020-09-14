import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import { FIXTURE_STORE_STATUSES } from 'tests/__data__/storeStatuses'
import Component, { DEFAULT_FILTERS } from '~/components/fleet-members/FilterSorter.vue'
import * as Stories from '~/components/fleet-members/FilterSorter.stories'
import { FIXTURE_STORE_TAGS } from '../../__data__/storeTags'
import { FIXTURE_USERS } from '../../__data__/users'
import { SORTABLES } from '../../../../resources/js/store/modules/stores'
import { FIXTURE_USER } from '../../__data__/user'

describe('components/stores/FilterSorter', () => {
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Set', () => {
      const wrapper = mount(Stories.Set())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
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
        tag: FIXTURE_STORE_TAGS.slice(0, 2),
        state_of_incorporation: 'Atlanta',
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
        statuses: FIXTURE_STORE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_STORE_STATUSES)
    })
    test('sortables', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.sortables).toHaveLength(0)

      wrapper.setProps({
        sortables: SORTABLES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.sortables).toMatchObject(SORTABLES)
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('clearFilters()', async () => {
      const resetTerm = jest.fn()
      const clearTerm = jest.fn()
      const wrapper = shallowMount(Component, {
        stubs: {
          'f-autocomplete': true,
          'multi-simple': true
        },
        propsData: {
          statuses: FIXTURE_STORE_STATUSES,
          sortables: SORTABLES,
          filters: {
            status_id: [1, 2],
            tag: FIXTURE_STORE_TAGS.slice(0, 2),
            state_of_incorporation: 'Atlanta',
            owner_uuid: FIXTURE_USERS[0].uuid
          }
        }
      })
      expect(wrapper.vm.filters.status_id).not.toBeNull()
      expect(wrapper.vm.filters.tag).not.toBeNull()
      expect(wrapper.vm.filters.state_of_incorporation).not.toBeNull()
      expect(wrapper.vm.filters.owner_uuid).not.toBeNull()
      wrapper.vm.$refs = {
        tag: {
          resetTerm
        },
        owner: {
          clearTerm
        }
      }
      wrapper.vm.clearFilters()
      await wrapper.vm.$nextTick()
      expect(resetTerm).toHaveBeenCalled()
      expect(clearTerm).toHaveBeenCalled()
      expect(wrapper.vm.filters.status_id).toBeNull()
      expect(wrapper.vm.filters.tag).toBeNull()
      expect(wrapper.vm.filters.state_of_incorporation).toBeNull()
      expect(wrapper.vm.filters.owner_uuid).toBeNull()
    })
    test('run(params)', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      const filters = {
        status_id: [ 1, 2 ],
        tag: [ { uuid: 'tag111' } ],
        state_of_incorporation: 'ATL',
        owner_uuid: 'owner111'
      }
      wrapper.setProps({
        statuses: FIXTURE_STORE_STATUSES,
        filters
      })
      wrapper.vm.run({})
      const emitted = wrapper.emitted().runFilter
      expect(emitted).toBeTruthy()
      const runParams = emitted[0][0]
      expect(runParams['status_id']).toEqual(filters.status_id)
      expect(runParams['tag']).toEqual([filters.tag[0].uuid])
      expect(runParams['state_of_incorporation']).toEqual(filters.state_of_incorporation)
      expect(runParams['owner_uuid']).toEqual(filters.owner_uuid)
    })
    test('onStateChanged(value)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.filters.state_of_incorporation).toBeNull()

      // Test should not wait for time. This is why onStateChanged method is being mocked
      wrapper.vm.onStateChanged = jest.fn((value) => {
        wrapper.vm.filters.state_of_incorporation = value
      })
      wrapper.vm.onStateChanged('Atlanta')
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.filters.state_of_incorporation).toEqual('Atlanta')
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
