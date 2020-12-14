import { mount, shallowMount } from '@vue/test-utils'
import Component, { DEFAULT_VALUE } from '~/components/users/Login.vue'
import * as Stories from '~/components/users/Login.stories'

describe('components/users/Login', () => {
  describe('Snapshots', () => {
    it('Basic', async () => {
      const wrapper = mount(Stories.Basic())
      expect(wrapper.element).toMatchSnapshot()
    })
    it('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      expect(wrapper.element).toMatchSnapshot()
    })
    it('WithError', async () => {
      const wrapper = mount(Stories.WithError())
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Props & Computed', () => {
    test('logo', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.logo).not.toBeDefined()

      wrapper.setProps({
        logo: '/images/logo.png'
      })
      expect(wrapper.vm.logo).toEqual('/images/logo.png')
    })
    test('title', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.title).not.toBeDefined()

      const title = 'FoodFleet'
      wrapper.setProps({
        title
      })
      expect(wrapper.vm.title).toEqual(title)
    })
    test('isLoading', () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toEqual(false)

      wrapper.setProps({
        isLoading: true
      })
      expect(wrapper.vm.isLoading).toEqual(true)
    })
  })

  describe('Methods', () => {
    describe('save()', () => {
      test('default', () => {
        const wrapper = mount(Component)
        wrapper.vm.save()
        let emitted = wrapper.emitted().input
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject(DEFAULT_VALUE)
      })
      test('set', async () => {
        const wrapper = mount(Component)
        const payload = {
          email: 'john.doe@domain.fr',
          password: 'pass123'
        }
        wrapper.setProps({
          value: payload
        })
        await wrapper.vm.$nextTick()
        wrapper.vm.save()
        const emitted = wrapper.emitted().input
        expect(emitted).toBeTruthy()
        expect(emitted[0][0]).toMatchObject(payload)
      })
    })

    test('passwordForgot()', () => {
      const wrapper = mount(Component)
      wrapper.vm.passwordForgot()
      let emitted = wrapper.emitted()['password-forgot']
      expect(emitted).toBeTruthy()
    })

    test('registerAs(type)', () => {
      const wrapper = mount(Component)
      wrapper.vm.registerAs('supplier')
      let emitted = wrapper.emitted()['register-as']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toEqual('supplier')
    })
  })
})
