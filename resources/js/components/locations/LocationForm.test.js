import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from './LocationForm.stories'
import Component from './LocationForm'
import { FIXTURE_LOCATION } from '../../../../tests/Javascript/__data__/locations'

describe('components/locations/LocationForm', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('value', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        value: FIXTURE_LOCATION
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.value).toMatchObject(FIXTURE_LOCATION)
    })
  })

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('save()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.setProps({
        value: FIXTURE_LOCATION
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.save()
      const emitted = wrapper.emitted().input
      expect(emitted).toBeTruthy()
      const saveData = emitted[0][0]
      expect(saveData).toMatchObject(FIXTURE_LOCATION)
    })

    test('onCancel()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.onCancel()
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
  })
})
