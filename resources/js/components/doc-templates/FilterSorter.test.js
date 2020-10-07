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
          term: 'abc',
          updated_at: '2020-10-01',
          updated_by_uuid: user.uuid,
          status_id: [2]
        }
        wrapper.vm.run(params)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted().runFilter
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject({
          title: params.term,
          updated_at: params.updated_at,
          updated_by_uuid: params.updated_by_uuid,
          status_id: params.status_id
        })
      })
    })

    test('clearFilters(params)', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.status_id).toBeNull()
    })
    test('selectModifiedBy(user)', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.updated_by_uuid).toBeUndefined()
      const runMock = jest.fn()
      wrapper.vm.$refs = {
        filter: {
          run: runMock
        }
      }

      const user = FIXTURE_USERS[0]
      wrapper.vm.selectModifiedBy(user)
      expect(runMock).toHaveBeenCalled()
      expect(wrapper.vm.updated_by_uuid).toEqual(user.uuid)
    })
  })
})
