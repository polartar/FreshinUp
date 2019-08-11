import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/reportables/Filter.vue'

describe('Modifier', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('run() emits runFilter', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.run({ term: 'test' })
      expect(wrapper.emitted().runFilter).toBeTruthy()
      expect(wrapper.emitted().runFilter).toHaveLength(1)
      expect(wrapper.emitted().runFilter[0]).toEqual([{
        name: 'test'
      }])
    })
  })
})
