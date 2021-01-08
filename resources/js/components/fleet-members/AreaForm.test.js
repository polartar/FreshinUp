import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from './AreaForm.stories'
import Component from './AreaForm.vue'

describe('components/fleet-members/AreaForm', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default(), {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading(), {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData(), {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Data', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
  })

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('onCancel()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      wrapper.vm.onCancel()
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
  })
})
