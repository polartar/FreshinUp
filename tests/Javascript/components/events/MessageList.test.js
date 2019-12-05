import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/MessageList.vue'

describe('MessageList', () => {
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
  describe('Filters', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('formatName Fitler with name', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          messages: [ { uuid: 1, owner: { name: 'John Smith' } } ]
        }
      })
      expect(wrapper.html()).toContain('JS')
    })
    test('formatName Fitler with null', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          messages: [ { uuid: 1, owner: { name: null } } ]
        }
      })
      expect(wrapper.html()).not.toContain('JS')
    })
  })
})
