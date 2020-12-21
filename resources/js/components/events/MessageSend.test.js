import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/MessageSend.vue'

describe('MessageSend', () => {
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('send() emits send-message', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.send()
      expect(wrapper.emitted()['send-message']).toBeTruthy()
    })
  })
})
