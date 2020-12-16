import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './RegisterSteps.stories'
import { USER_TYPE } from '~/store/modules/userTypes'
import Component, { DEFAULT_VALUE } from './RegisterSteps'

describe('components/RegisterSteps', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default(), {
        mocks: {
          errors: {
            collect: jest.fn()
          },
          $el: {
            querySelector: jest.fn()
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
          },
          $el: {
            querySelector: jest.fn()
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated(), {
        mocks: {
          errors: {
            collect: jest.fn()
          },
          $el: {
            querySelector: jest.fn()
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
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
    test('current', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.current).toBeFalsy()

      wrapper.setProps({
        current: 2
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.current).toEqual(2)
    })
    test('typeId', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.typeId).toEqual(USER_TYPE.CUSTOMER)

      wrapper.setProps({
        typeId: 3
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.typeId).toEqual(3)
    })
    test('value', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.value).toMatchObject(DEFAULT_VALUE)

      const value = {
        first_name: 'Hugo',
        last_name: 'Boss',
        email: 'hugo@boss.com',
        phone_number: '4984894894',
        password: 'secret',
        password_confirmation: 'secret'
      }
      wrapper.setProps({
        value
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.value).toMatchObject(value)
    })
    test('isForSupplier', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.isForSupplier).toEqual(false)

      wrapper.setProps({
        typeId: 1
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isForSupplier).toEqual(true)

      wrapper.setProps({
        typeId: 2
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isForSupplier).toEqual(false)
    })
    test('isForCustomer', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.isForCustomer).toEqual(true)

      wrapper.setProps({
        typeId: USER_TYPE.SUPPLIER
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isForCustomer).toEqual(false)

      wrapper.setProps({
        typeId: USER_TYPE.CUSTOMER
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isForCustomer).toEqual(true)
    })
    test('title', async () => {
      const wrapper = shallowMount(Component, {
        propsData: {
          typeId: 0
        },
        mocks: {
          errors: {
            collect: jest.fn()
          }
        }
      })
      expect(wrapper.vm.title).toEqual('')

      wrapper.setProps({
        typeId: USER_TYPE.SUPPLIER
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.title).toEqual('Join our Fleet!')

      wrapper.setProps({
        typeId: USER_TYPE.CUSTOMER
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.title).toEqual('Sign up as a customer to book an event!')
    })
    test('description', async () => {
      const wrapper = shallowMount(Component, {
        mocks: {
          errors: {
            collect: jest.fn()
          }
        },
        propsData: {
          typeId: 0
        }
      })
      expect(wrapper.vm.description).toEqual('')

      wrapper.setProps({
        typeId: USER_TYPE.SUPPLIER
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.description).toEqual('You will be instructed on how to register and manage your fleet once your account is activated.')

      wrapper.setProps({
        typeId: USER_TYPE.CUSTOMER
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.description).toEqual('You will be instructed on how to book and manage your company\'s events once your account is activated.')
    })
  })
})
