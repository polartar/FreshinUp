import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/docs/BasicInfoForm.vue'

describe('BasicInfoForm', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('defaults', () => {
      const wrapper = mount(Component, {
        localVue: localVue
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
        localVue: localVue
      })
      wrapper.vm.doc.title = 'mock'
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted()['data-change']).toBeTruthy()
    })
  })
})
