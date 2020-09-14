import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/BasicInformation.stories'
import Component from '~/components/fleet-members/BasicInformation.vue'
import { FIXTURE_STORE } from '../../__data__/stores'

describe('components/fleet-members/BasicInformation', () => {
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
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('loading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.loading).toBe(false)

      wrapper.setProps({
        loading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.loading).toBe(true)
    })
    test('value', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        value: FIXTURE_STORE
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.value).toMatchObject(FIXTURE_STORE)
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
        value: FIXTURE_STORE
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.save()
      const emitted = wrapper.emitted().input
      expect(emitted).toBeTruthy()
      const saveData = emitted[0][0]
      expect(saveData).toMatchObject(FIXTURE_STORE)
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

    test('onDeleteMember()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.setProps({
        value: FIXTURE_STORE
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.onDeleteMember()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().delete
      expect(emitted).toBeTruthy()
      const deleteData = emitted[0][0]
      expect(deleteData).toMatchObject(FIXTURE_STORE)
    })
  })
})
