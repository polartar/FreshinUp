import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_DOCUMENT } from 'tests/__data__/documents'
import { FIXTURE_DOCUMENT_TYPES } from 'tests/__data__/documentTypes'
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
        localVue: localVue,
        propsData: {
          types: FIXTURE_DOCUMENT_TYPES,
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
          types: FIXTURE_DOCUMENT_TYPES
        }
      })
      wrapper.vm.doc.title = 'mock'
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted()['data-change']).toBeTruthy()
      expect(wrapper.emitted()['data-change'][0][0].title).toEqual('mock')
    })
  })
})
