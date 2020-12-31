import { mount, shallowMount } from '@vue/test-utils'
import { FIXTURE_STORE_TAGS } from '../../../../tests/Javascript/__data__/storeTags'
import Component from './AddStoreFilterSorter'
import * as Stories from './AddStoreFilterSorter.stories'

describe('components/fleet-members/AddStoreFilterSorter', () => {

  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {

    test('showFilters()', () => {
      const wrapper = shallowMount(Component)

      wrapper.setData({
        showFilters: false
      })

      expect(wrapper.vm.showFilters).toBeFalsy()

      wrapper.vm.toggleShowFilter()
      expect(wrapper.vm.showFilters).toBeTruthy()

      wrapper.vm.toggleShowFilter()
      expect(wrapper.vm.showFilters).toBeFalsy()
    })

    test('clearAllFilters()', async () => {
      const wrapper = shallowMount(Component)

      wrapper.setProps({
        value: {
          state_of_incorporation: 'aaa',
          type_id: 2,
          tags: FIXTURE_STORE_TAGS
        }
      })
      await wrapper.vm.$nextTick()
      const mock = jest.fn()
      wrapper.vm.$refs = {
        tag: {
          resetTerm: mock
        }
      }
      wrapper.vm.clearFilters()
      expect(mock).toHaveBeenCalled()
      expect(wrapper.vm.name).toBe('')
      expect(wrapper.vm.state_of_incorporation).toBe('')
      expect(wrapper.vm.type_id).toBe('')
      expect(wrapper.vm.tags).toEqual([])
    })
  })
})
