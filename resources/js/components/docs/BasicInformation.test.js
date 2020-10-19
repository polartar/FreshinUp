import Component, { DEFAULT_DOCUMENT } from './BasicInformation'
import * as Stories from './BasicInformation.stories'
import { mount, shallowMount } from '@vue/test-utils'
import { FIXTURE_DOCUMENTS } from 'tests/__data__/documents'
import { FIXTURE_DOCUMENT_TEMPLATES } from 'tests/__data__/documentTemplates'
import { FIXTURE_DOCUMENT_TYPES } from 'tests/__data__/documentTypes'
describe('components/docs/BasicInformation', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
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
    test('value', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.value).toMatchObject(DEFAULT_DOCUMENT)

      const template = FIXTURE_DOCUMENT_TEMPLATES[0]
      wrapper.setProps({
        value: template
      })
      expect(wrapper.vm.value).toMatchObject(template)
    })
    test('isLoading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isLoading).toBe(false)

      wrapper.setProps({
        isLoading: true
      })
      expect(wrapper.vm.isLoading).toBe(true)
    })
    test('types', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.types).toHaveLength(0)

      wrapper.setProps({
        types: FIXTURE_DOCUMENT_TYPES
      })
      expect(wrapper.vm.types).toMatchObject(FIXTURE_DOCUMENT_TYPES)
    })
    test('templates', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.templates).toHaveLength(0)

      wrapper.setProps({
        templates: FIXTURE_DOCUMENT_TEMPLATES
      })
      expect(wrapper.vm.templates).toMatchObject(FIXTURE_DOCUMENT_TEMPLATES)
    })

    // computed
    test('submitLabel', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.submitLabel).toEqual('Submit')

      wrapper.setProps({
        value: {
          uuid: 'abc123'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.submitLabel).toEqual('Save changes')
    })

    test('ownerName', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.ownerName).toBeUndefined()

      wrapper.setProps({
        value: {
          owner: {
            name: FIXTURE_DOCUMENTS[0].owner.name
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.ownerName).toEqual(FIXTURE_DOCUMENTS[0].owner.name)
    })

    test('createdAt', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.createdAt).toBeNull()

      wrapper.setProps({
        value: {
          created_at: '2019-09-30T03:51:14.000000Z'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.createdAt).toEqual('Sep 30, 2019 • 03:51 am')
    })

    test('downloadable', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.downloadable).toBe(false)

      wrapper.setProps({
        value: {
          type_id: 1
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.downloadable).toBe(false)

      wrapper.setProps({
        value: {
          type_id: 2
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.downloadable).toBe(true)
    })

    test('previewOrDownloadLabel', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.previewOrDownloadLabel).toEqual('Preview')

      wrapper.setProps({
        value: {
          type_id: 2
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.previewOrDownloadLabel).toEqual('Download')
    })

    test('isNew', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isNew).toBe(true)

      wrapper.setProps({
        value: {
          uuid: 'abc123'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isNew).toBe(false)
    })
  })

  describe('Methods', () => {
    test('download()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.download()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().download
      expect(emitted).toBeTruthy()
    })

    test('preview()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.preview()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().preview
      expect(emitted).toBeTruthy()
    })

    test('cancel()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.cancel()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().cancel
      expect(emitted).toBeTruthy()
    })

    test('selectAssigned(assigned)', async () => {
      const wrapper = shallowMount(Component)
      const assigned = {
        uuid: 'abc123',
        event_store_uuid: 'store123'
      }
      wrapper.vm.selectAssigned(assigned)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.assigned_uuid).toEqual(assigned.uuid)
      expect(wrapper.vm.event_store_uuid).toEqual(assigned.event_store_uuid)
    })

    test('changeAssignedType(assigned)', async () => {
      const wrapper = shallowMount(Component)
      const value = 'event'
      wrapper.vm.changeAssignedType(value)
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.assigned_type).toEqual(value)
    })
  })
})
