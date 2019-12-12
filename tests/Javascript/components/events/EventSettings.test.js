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

      wrapper.vm.selectedIntervalUnit = 'Week(s)'
      wrapper.vm.selectedIntervalValue = 2
      wrapper.vm.selectedRepeatOnWeek = [0, 2]
      wrapper.vm.selectedEndsOn = 'on'

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['intervalUnit']).toBe('Week(s)')
      expect(formData['intervalValue']).toBe(2)
      expect(formData['repeatOn']).toMatchObject([
        { id: 1, text: 'Sunday' },
        { id: 3, text: 'Tuesday' }
      ])
      expect(formData['endsOn']).toBe('on')
    })

    test('save function return correct form data while setting events repeat on month(s)', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.selectedIntervalUnit = 'Month(s)'
      wrapper.vm.selectedIntervalValue = 2
      wrapper.vm.selectedRepeatOnMonth = 1
      wrapper.vm.selectedEndsOn = 'on'

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['intervalUnit']).toBe('Month(s)')
      expect(formData['intervalValue']).toBe(2)
      expect(formData['repeatOn']).toMatchObject([
        { id: 1, text: 'First Monday on each following month' }
      ])
      expect(formData['endsOn']).toBe('on')
    })

    test('save function return correct form data while setting events repeat on year(s)', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.selectedIntervalUnit = 'Year(s)'
      wrapper.vm.selectedIntervalValue = 2
      wrapper.vm.selectedEndsOn = 'on'

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['intervalUnit']).toBe('Year(s)')
      expect(formData['intervalValue']).toBe(2)
      expect(formData['endsOn']).toBe('on')
    })

    test('save function return correct form data while ends on set as "after"', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.selectedIntervalUnit = 'Week(s)'
      wrapper.vm.selectedIntervalValue = 2
      wrapper.vm.selectedRepeatOnWeek = [0, 2]
      wrapper.vm.selectedEndsOn = 'after'
      wrapper.vm.selectedOccurrences = 2

      wrapper.vm.save()

      const formData = wrapper.emitted()['save'][0][0]

      expect(formData['intervalUnit']).toBe('Week(s)')
      expect(formData['intervalValue']).toBe(2)
      expect(formData['repeatOn']).toMatchObject([
        { id: 1, text: 'Sunday' },
        { id: 3, text: 'Tuesday' }
      ])
      expect(formData['endsOn']).toBe('after')
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
