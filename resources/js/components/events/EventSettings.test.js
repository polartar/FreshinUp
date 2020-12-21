import { shallowMount, mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import Component from '~/components/events/EventSettings'

describe('Document list component', () => {
  let localVue

  test('Snapshots', () => {
    const vue = createLocalVue({ validation: true })
    localVue = vue.localVue
    const wrapper = mount(Component, {
      localVue
    })
    expect(wrapper.element).toMatchSnapshot()
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('save function return correct form data while setting events repeat on week(s)', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.scheduleData.interval_unit = 'Week(s)'
      wrapper.vm.scheduleData.interval_value = 2
      wrapper.vm.selectedRepeatOnWeek = [0, 2]
      wrapper.vm.scheduleData.ends_on = 'on'

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['interval_unit']).toBe('Week(s)')
      expect(formData['interval_value']).toBe(2)
      expect(formData['repeat_on']).toMatchObject([
        { id: 1, text: 'Sunday' },
        { id: 3, text: 'Tuesday' }
      ])
      expect(formData['ends_on']).toBe('on')
    })

    test('save function return correct form data while setting events repeat on month(s)', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.scheduleData.interval_unit = 'Month(s)'
      wrapper.vm.scheduleData.interval_value = 2
      wrapper.vm.selectedRepeatOnMonth = 1
      wrapper.vm.scheduleData.ends_on = 'on'

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['interval_unit']).toBe('Month(s)')
      expect(formData['interval_value']).toBe(2)
      expect(formData['repeat_on']).toMatchObject([
        { id: 1, text: 'First Monday on each following month' }
      ])
      expect(formData['ends_on']).toBe('on')
    })

    test('save function return correct form data while setting events repeat on year(s)', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.scheduleData.interval_unit = 'Year(s)'
      wrapper.vm.scheduleData.interval_value = 2
      wrapper.vm.scheduleData.ends_on = 'on'

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['interval_unit']).toBe('Year(s)')
      expect(formData['interval_value']).toBe(2)
      expect(formData['ends_on']).toBe('on')
    })

    test('save function return correct form data while ends on set as "after"', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.scheduleData.interval_unit = 'Week(s)'
      wrapper.vm.scheduleData.interval_value = 2
      wrapper.vm.selectedRepeatOnWeek = [0, 2]
      wrapper.vm.scheduleData.ends_on = 'after'
      wrapper.vm.scheduleData.occurrences = 2

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['interval_unit']).toBe('Week(s)')
      expect(formData['interval_value']).toBe(2)
      expect(formData['repeat_on']).toMatchObject([
        { id: 1, text: 'Sunday' },
        { id: 3, text: 'Tuesday' }
      ])
      expect(formData['ends_on']).toBe('after')
      expect(formData['occurrences']).toBe(2)
    })

    test('cancel function emitted cancel action and close modal', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.cancel()

      expect(wrapper.emitted()['cancel']).toBeTruthy()
    })
  })
})
