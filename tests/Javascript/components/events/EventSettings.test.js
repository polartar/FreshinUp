import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/events/EventSettings'

describe('Document list component', () => {
  let localVue

  test('Snapshots', () => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
    const wrapper = mount(Component, {
      localVue
    })
    expect(wrapper.element).toMatchSnapshot()
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('save function emitted save action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.save()

      expect(wrapper.emitted()['save']).toBeTruthy()
    })

    test('cancel function emitted cancel action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.cancel()

      expect(wrapper.emitted()['cancel']).toBeTruthy()
    })
  })
})
