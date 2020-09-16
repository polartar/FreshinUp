import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/AreaForm.stories'

import Component from '~/components/fleet-members/AreaForm.vue'
import MapValueKeysToData from '../../mixins/MapValueKeysToData'

describe('components/fleet-members/AreaForm', () => {
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

  test('has mixin MapValueKeysToData', async () => {
    const wrapper = shallowMount(Component)
    expect(wrapper.vm.mixins).toContain(MapValueKeysToData)
  })

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('onCancel()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.vm.onCancel()
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })
  })
})
