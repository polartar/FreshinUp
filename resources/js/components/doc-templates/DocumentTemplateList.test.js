import { shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/venues/VenueList.vue'
import * as Stories from '~/components/venues/VenueList.stories'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'
import { FIXTURE_DOCUMENT_TEMPLATE_STATUSES } from '../../../../tests/Javascript/__data__/documentTemplateStatuses'

describe('components/doc-templates/DocumentTemplateList', () => {
  describe('Snapshots', () => {
    test('Empty', async () => {
      const wrapper = mount(Stories.Empty())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('IsLoading', async () => {
      const wrapper = mount(Stories.IsLoading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Populated', async () => {
      const wrapper = mount(Stories.Populated())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('items', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.items).toHaveLength(0)

      wrapper.setProps({
        items: FIXTURE_DOCUMENT_TEMPLATES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.items).toMatchObject(FIXTURE_DOCUMENT_TEMPLATES)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_DOCUMENT_TEMPLATE_STATUSES
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_DOCUMENT_TEMPLATE_STATUSES)
    })
  })
})
