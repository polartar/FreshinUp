import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/MessageList.vue'
import { FIXTURE_MESSAGES } from 'tests/__data__/messages'

describe('MessageList', () => {
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Props & Computed', () => {
    test('messages', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.messages).toMatchObject({})

      wrapper.setProps({
        messages: FIXTURE_MESSAGES
      })
      expect(wrapper.vm.messages).toMatchObject(FIXTURE_MESSAGES)
    })
  })
})
