import { mount, shallowMount } from '@vue/test-utils'
import Component from './FilterSorter.vue'
import * as Stories from './FilterSorter.stories'
import { FIXTURE_VENUE_STATUSES } from '../../../../tests/Javascript/__data__/venueStatuses'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'

describe('components/doc-templates/FilterSorter', () => {
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
  })

  describe('Methods', () => {
    const getRunParamsMock = jest.fn(() => ({ term: 'some term' }))
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
        const user = FIXTURE_USERS[0]
        const params = {
          term: 'abc'
        }
        wrapper.vm.$refs = {
          filter: {
            getRunParams: getRunParamsMock
          }
        }
        const filters = {
          updated_at: '2020-10-01',
          updated_by_uuid: user.uuid,
          status_id: [2]
        }
        wrapper.setProps({
          filters
        })
        wrapper.vm.run(params)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted().runFilter
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject({
          title: params.term,
          updated_at: filters.updated_at,
          updated_by_uuid: filters.updated_by_uuid,
          status_id: filters.status_id
        })
      })
    })

    test('clearFilters(params)', () => {
      const wrapper = shallowMount(Component)
      const clearTermMock = jest.fn()
      wrapper.vm.$refs = {
        filter: {
          getRunParams: getRunParamsMock
        },
        updatedBy: {
          clearTerm: clearTermMock
        }
      }
      wrapper.vm.clearFilters({})
      expect(clearTermMock).toHaveBeenCalled()
      expect(wrapper.vm.filters.status_id).toBeNull()
      expect(wrapper.vm.filters.updated_at).toBeNull()
      expect(wrapper.vm.filters.updated_by_uuid).toBeNull()
    })
    test('selectModifiedBy(user)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.filters.updated_by_uuid).toBeFalsy()
      const runMock = jest.fn()
      wrapper.vm.$refs = {
        filter: {
          run: runMock,
          getRunParams: getRunParamsMock
        }
      }

      const user = FIXTURE_USERS[0]
      wrapper.vm.selectModifiedBy(user)
      expect(runMock).toHaveBeenCalled()
      expect(wrapper.vm.filters.updated_by_uuid).toEqual(user.uuid)
    })
  })
})
