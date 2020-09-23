import { mount, shallowMount } from '@vue/test-utils'
import Component from './DocumentList.vue'
import * as Stories from './DocumentList.stories'
import { FIXTURE_DOCUMENTS } from '../../../../tests/Javascript/__data__/documents'
import { FIXTURE_DOCUMENT_STATUSES } from '../../../../tests/Javascript/__data__/documentStatuses'

describe('components/venues/DocumentList', () => {
  describe('Snapshots', () => {
    test('Empty', () => {
      const wrapper = mount(Stories.Empty())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', () => {
      const wrapper = mount(Stories.Loading())
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', () => {
      const wrapper = mount(Stories.Populated())
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('items', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_DOCUMENTS
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_DOCUMENTS)
    })
    test('statuses', async () => {
      const wrapper = mount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_DOCUMENT_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_DOCUMENT_STATUSES)
    })
  })

  describe('Methods', () => {
    test('changeStatus', async () => {
      const wrapper = shallowMount(Component)
      const value = 2
      const document = FIXTURE_DOCUMENTS[0]
      wrapper.vm.changeStatus(value, document)
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted()['change-status']
      expect(emitted).toBeTruthy()
      expect(emitted[0][0]).toEqual(value)
      expect(emitted[0][1]).toMatchObject(document)
    })
  })
})
