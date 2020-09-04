import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/BasicInformation.stories'
import Component from '~/components/fleet-members/BasicInformation.vue'
import { FIXTURE_FLEET_MEMBER } from '../../__data__/fleet-members'

describe('fleet-members/BasicInformation', () => {
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

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('On save changes', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        memberData: FIXTURE_FLEET_MEMBER
      })

      wrapper.vm.onSaveChanges()
      expect(wrapper.emitted().save).toBeTruthy()
      const saveData = wrapper.emitted().save[0][0]
      expect(saveData).toEqual(FIXTURE_FLEET_MEMBER)
    })

    test('On cancel', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.vm.onCancel()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })

    test('On delete member', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })

      wrapper.setData({
        memberData: FIXTURE_FLEET_MEMBER
      })

      wrapper.vm.onDeleteMember()
      expect(wrapper.emitted().delete).toBeTruthy()
      const deleteData = wrapper.emitted().delete[0][0]
      expect(deleteData).toEqual(FIXTURE_FLEET_MEMBER)
    })
  })
})
