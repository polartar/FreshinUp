import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/EventSettingsModal.vue'

describe('EventSettingsModal', () => {
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
      expect(wrapper.vm.formData).toEqual([])
      expect(wrapper.vm.selectedDate).toBe('')
    })

    test('save() emits save with formData', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      const formData = {
        intervalUnit: 'Week(s)',
        intervalValue: 2,
        repeatOn: [{ id: 1, text: 'Sunday' }],
        endsOn: 'after',
        occurrences: 3
      }
      wrapper.vm.save(formData)
      expect(wrapper.emitted()['save']).toBeTruthy()
      expect(wrapper.vm.formData).toBe(formData)
      expect(wrapper.emitted()['save'][0][0]).toEqual({
        ...formData,
        description: wrapper.vm.selectedDate
      })
    })
  })
})
