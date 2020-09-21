import { mount, shallowMount } from '@vue/test-utils'
import Component from './EventFilterSorter.vue'
import * as Stories from './EventFilterSorter.stories'

describe('components/venues/EventFilterSorter', () => {
  describe('Snapshots', () => {
    test('Default', () => {
      const wrapper = mount(Stories.Default())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Set', () => {
      const wrapper = mount(Stories.Set())
      expect(wrapper.element).toMatchSnapshot()
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
          term: 'abc',
          orderBy: 'created_at'
        }
        wrapper.vm.run(params)
        await wrapper.vm.$nextTick()
        const emitted = wrapper.emitted().runFilter
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject({
          'filter[name]': params.term,
          sort: params.orderBy
        })
      })
    })
  })
})
