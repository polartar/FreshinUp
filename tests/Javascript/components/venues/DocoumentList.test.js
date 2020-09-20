import { mount } from '@vue/test-utils'
import Component from '~/components/venues/DocumentList'
import * as Stories from '~/components/venues/DocumentList.stories'
import { FIXTURE_DOCUMENT_STATUSES } from '../../__data__/documentStatuses'
import { FIXTURE_DOCUMENTS } from '../../__data__/documents'

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
})
