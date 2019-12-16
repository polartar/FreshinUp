import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/BasicInformation.vue'
import { FIXTURE_EVENTS } from 'tests/__data__/events'

describe('event/BasicInformation', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('default', () => {
      const wrapper = mount(Component, {
        localVue: localVue
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('event set', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        event: FIXTURE_EVENTS[0]
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('event set and read-only', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        event: FIXTURE_EVENTS[0],
        readOnly: true
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('cancel() emits cancel', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          event: FIXTURE_EVENTS[0]
        }
      })
      wrapper.vm.cancel()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
    test('delete() emits delete', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.cancel()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
    test('save() emits save with value', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.eventData = {
        name: 'Test event',
        manager_uuid: 'aaaa',
        host_uuid: 'bbbb',
        budget: 10,
        attendees: 5,
        commission_rate: 7,
        commission_type: 2,
        event_tags: ['tag 1', 'tag 2'],
        start_at: '2019-10-10 11:04',
        end_at: '2019-10-12 11:04'
      }
      wrapper.vm.save()
      expect(wrapper.emitted().save).toBeTruthy()
      expect(wrapper.emitted().save).toHaveLength(1)
      expect(wrapper.emitted().save[0]).toEqual([{
        name: 'Test event',
        manager_uuid: 'aaaa',
        host_uuid: 'bbbb',
        budget: 10,
        attendees: 5,
        commission_rate: 7,
        commission_type: 2,
        event_tags: ['tag 1', 'tag 2'],
        start_at: '2019-10-10 11:04',
        end_at: '2019-10-12 11:04'
      }])
    })
    test('selectManager() set manager_uuid', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.selectManager({ uuid: 'aaaa' })
      expect(wrapper.vm.eventData.manager_uuid).toEqual('aaaa')
    })
    test('selectHost() set host_uuid', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.selectHost({ uuid: 'bbbb' })
      expect(wrapper.vm.eventData.host_uuid).toEqual('bbbb')
    })
  })
})
