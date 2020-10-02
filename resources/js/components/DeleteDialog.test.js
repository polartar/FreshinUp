import { mount, shallowMount } from '@vue/test-utils'
import * as Stories from './DeleteDialog.stories'
import Component from './DeleteDialog.vue'
import { FIXTURE_STORES } from '../../../tests/Javascript/__data__/stores'

describe('components/DeleteDialog', () => {
  describe('Snapshots', () => {
    test('Basic', async () => {
      const wrapper = mount(Stories.Basic())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('itemTitleProp', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.itemTitleProp).toEqual('title')

      wrapper.setProps({
        itemTitleProp: 'name'
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.itemTitleProp).toEqual('name')
    })
    test('title', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.title).toEqual('Are you sure you want to delete the selected item(s)')

      wrapper.setProps({
        title: 'Trash them all ?'
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.title).toEqual('Trash them all ?')
    })
    test('items', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_STORES // any array of items
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toEqual(FIXTURE_STORES)
    })
    test('progress', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.progress).toEqual(0)

      wrapper.setProps({
        progress: 67
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.progress).toEqual(67)
    })
    test('progressStatus', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.progressStatus).toEqual('0 %')

      wrapper.setProps({
        progress: 67
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.progressStatus).toEqual('67 %')
    })
    test('message', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.message).toEqual('')

      wrapper.setProps({
        items: FIXTURE_STORES // any array of items
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.message).toEqual(FIXTURE_STORES.map(item => item.title).join(', '))
    })
  })
})
