import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_DOCUMENT } from 'tests/__data__/documents'
import Component from '~/components/docs/PublishingForm.vue'

describe('PublishingForm', () => {
  // Component instance "under test"
  let localVue, mock
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock.onGet('foodfleet/stores').reply(200, {
        data: [
          { name: 'eligendi', uuid: '0623e163-d229-4fe9-b54f-6bbfd5b559e0' }
        ]
      })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })
    afterEach(() => {
      mock.restore()
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
      mock = vue.mock
      mock.onGet('foodfleet/stores').reply(200, {
        data: [
          { name: 'eligendi', uuid: '0623e163-d229-4fe9-b54f-6bbfd5b559e0' }
        ]
      })
        .onAny().reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
    })

    afterEach(() => {
      mock.restore()
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

      wrapper.vm.selectAssigned({
        uuid: 'mock'
      })
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
