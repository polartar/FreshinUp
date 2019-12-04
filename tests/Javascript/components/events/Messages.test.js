import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/events/Messages.vue'
import { FIXTURE_MESSAGES } from 'tests/__data__/messages'

describe('Messages component test', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('messages', () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          messages: FIXTURE_MESSAGES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('messages empty', () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          messages: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('send', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.send('message testing')

      expect(wrapper.emitted()['send-message']).toBeTruthy()

      expect(wrapper.emitted()['send-message'][0][0]).toEqual('message testing')
    })
  })
})
