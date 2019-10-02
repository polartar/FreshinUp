import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_DOCUMENT } from 'tests/__data__/document'
import Component from '~/components/docs/PublishingForm.vue'

describe('PublishingForm', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('defaults', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          isvalid: true,
          initdata: FIXTURE_DOCUMENT
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

    test('watch doc change emitted data-change', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          isvalid: true
        }
      })
      wrapper.vm.doc.assigned_type = 3
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted()['data-change']).toBeTruthy()
      expect(wrapper.emitted()['data-change'][0][0].assigned_type).toEqual(3)
    })

    test('selectAssigned function change doc assigned_uuid ', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          isvalid: true
        }
      })

      wrapper.vm.selectAssigned('mock')
      expect(wrapper.vm.doc.assigned_uuid).toBe('mock')
    })

    test('changeAssignedType function change doc assigned_type ', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          isvalid: true
        }
      })

      wrapper.vm.changeAssignedType(3)
      expect(wrapper.vm.doc.assigned_type).toBe(3)
    })

    test('onSaveClick function emitted data-save ', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          isvalid: true
        }
      })

      wrapper.vm.onSaveClick()
      expect(wrapper.emitted()['data-save']).toBeTruthy()
      expect(wrapper.emitted()['data-save'][0][0]).toBeFalsy()
    })
  })
})
