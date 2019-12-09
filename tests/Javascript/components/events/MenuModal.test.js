import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/MenuModal.vue'

describe('MenuModal', () => {
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

  describe('Computed', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('dialog', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: false
        }
      })
      expect(wrapper.vm.dialog).toBeFalsy()
      wrapper.vm.dialog = true
      expect(wrapper.emitted()['change']).toBeTruthy()
      expect(wrapper.emitted()['change'][0][0]).toBeTruthy()
    })
    test('title for new menu', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: true
        }
      })
      expect(wrapper.vm.title).toEqual('Add new menu item')
      wrapper.vm.menu = { id: 2, title: 'title 2' }
      expect(wrapper.vm.title).toEqual('Edit menu item')
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('cancel() emits change', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.cancel()
      expect(wrapper.emitted()['change']).toBeTruthy()
      expect(wrapper.vm.formValue).toEqual({})
    })
    test('save() emits save with formData', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: true,
          menu: { id: 1 }
        }
      })
      wrapper.vm.save()
      expect(wrapper.emitted()['save']).toBeTruthy()
      expect(wrapper.emitted()['save'][0][0]).toEqual({ id: 1 })
    })
  })

  describe('Watch', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('dialog changes with value', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: false
        }
      })
      wrapper.vm.value = true
      expect(wrapper.vm.dialog).toBeTruthy()
    })
  })
})
