import { mount, shallowMount } from '@vue/test-utils'
import Component from './FilterSorter.vue'
import * as Stories from './FilterSorter.stories'
import { FIXTURE_EVENT_STATUSES } from '../../../../tests/Javascript/__data__/eventStatuses'

describe('components/venues/Events', () => {
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_EVENT_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_EVENT_STATUSES)
    })
  })

  describe('Methods', () => {
    describe('run', () => {
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
          term: 'abc',
          sortBy: 'created_at'
        }
        wrapper.vm.run(params)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted().runFilter
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject(params)
      })
    })
  })
})
