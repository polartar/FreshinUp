import { mount, shallowMount } from '@vue/test-utils'

import * as Stories from './MenuItemForm.stories'
import Component from './MenuItemForm.vue'

describe('components/menu-items/MenuItemForm', () => {
  describe('Default', () => {
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
  test('WithServings', async () => {
    const wrapper = mount(Stories.WithServings(), {
      mocks: {
        errors: {
          collect: jest.fn()
        }
      }
    })
    await wrapper.vm.$nextTick()
    expect(wrapper.element).toMatchSnapshot()
  })
  test('WithoutServings', async () => {
    const wrapper = mount(Stories.WithoutServings(), {
      mocks: {
        errors: {
          collect: jest.fn()
        }
      }
    })
    await wrapper.vm.$nextTick()
    expect(wrapper.element).toMatchSnapshot()
  })

  describe('Props & Computed', () => {
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
    test('withoutServings', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.withoutServings).toBe(false)

      wrapper.setProps({
        withoutServings: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.withoutServings).toBe(true)
    })
  })

  describe('methods', () => {
    test('onCancel()', async () => {
      const wrapper = shallowMount(Component, {
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
