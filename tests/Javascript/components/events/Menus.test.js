import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/events/Menus.vue'
import { FIXTURE_MENUS } from 'tests/__data__/menus'

describe('Store List component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('menus', () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          menuTitle: "Kevin's Truck",
          menus: FIXTURE_MENUS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('menus empty', () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          menuTitle: '',
          menus: []
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

    test('edit test', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.edit(FIXTURE_MENUS[0])

      expect(wrapper.emitted()['manage-edit']).toBeTruthy()

      expect(wrapper.emitted()['manage-edit'][0][0]).toEqual(FIXTURE_MENUS[0])
    })

    test('del test', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.del(FIXTURE_MENUS[0])

      expect(wrapper.emitted()['manage-delete']).toBeTruthy()

      expect(wrapper.emitted()['manage-delete'][0][0]).toEqual(FIXTURE_MENUS[0])
    })

    test('multipleDelete test', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.multipleDelete(FIXTURE_MENUS)

      expect(wrapper.emitted()['manage-multiple-delete']).toBeTruthy()

      expect(wrapper.emitted()['manage-multiple-delete'][0][0]).toEqual(FIXTURE_MENUS)
    })

    test('create test', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.create()

      expect(wrapper.emitted()['create']).toBeTruthy()
    })

    test('save test', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.save(FIXTURE_MENUS[1])

      expect(wrapper.emitted()['save']).toBeTruthy()

      expect(wrapper.emitted()['save'][0][0]).toEqual(FIXTURE_MENUS[1])
    })
  })
})
