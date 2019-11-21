import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/MenuList.vue'
import { FIXTURE_MENUS } from 'tests/__data__/menus'

describe('Menu List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('menus set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          menus: FIXTURE_MENUS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('menus empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          menus: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('manage function emitted single manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const itemActions = [
        { action: 'edit', text: 'Edit' },
        { action: 'delete', text: 'Delete' }
      ]

      wrapper.vm.manage(itemActions[0], FIXTURE_MENUS[0])
      wrapper.vm.manage(itemActions[1], FIXTURE_MENUS[1])

      expect(wrapper.emitted()['manage-edit']).toBeTruthy()
      expect(wrapper.emitted()['manage-edit'][0][0]).toEqual(FIXTURE_MENUS[0])
      expect(wrapper.emitted()['manage-delete']).toBeTruthy()
      expect(wrapper.emitted()['manage-delete'][0][0]).toEqual(FIXTURE_MENUS[1])
    })

    test('manageMultiple function emitted multiple manage action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.manageMultiple('delete')

      expect(wrapper.emitted()['manage-multiple-delete']).toBeTruthy()
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('selectedActions', () => {
      const wrapper = shallowMount(Component)
      wrapper.setData({ selected: [] })
      expect(wrapper.vm.selectedActions).toEqual([])
      wrapper.setData({ selected: [ 1 ] })
      expect(wrapper.vm.selectedActions[0].action).toBe('delete')
      expect(wrapper.vm.selectedActions[0].text).toBe('Delete')
    })
  })
})
