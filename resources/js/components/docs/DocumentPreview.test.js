import * as Stories from './DocumentPreview.stories'
import Component, { DEFAULT_DOCUMENT } from './DocumentPreview'
import { mount, shallowMount } from '@vue/test-utils'
import { FIXTURE_DOCUMENTS } from 'tests/__data__/documents'
import { FIXTURE_EVENTS } from 'tests/__data__/events'
import { FIXTURE_DOCUMENT_TEMPLATES, FIXTURE_DOCUMENT_TEMPLATES_VARIABLES } from 'tests/__data__/documentTemplates'
import Mustache from 'mustache'

describe('components/docs/DocumentPreview', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
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

      const document = FIXTURE_DOCUMENTS[0]
      wrapper.setProps({
        value: document
      })
      expect(wrapper.vm.value).toMatchObject(document)
    })

    test('events', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.events).toHaveLength(0)

      wrapper.setProps({
        events: FIXTURE_EVENTS
      })
      expect(wrapper.vm.events).toMatchObject(FIXTURE_EVENTS)
    })

    test('templates', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.templates).toHaveLength(0)

      wrapper.setProps({
        templates: FIXTURE_DOCUMENT_TEMPLATES
      })
      expect(wrapper.vm.templates).toMatchObject(FIXTURE_DOCUMENT_TEMPLATES)
    })

    test('variables', async () => {
      const wrapper = shallowMount(Component)
      // expect(wrapper.vm.variables).toMatchObject({})

      wrapper.setProps({
        variables: FIXTURE_DOCUMENT_TEMPLATES_VARIABLES
      })
      expect(wrapper.vm.variables).toMatchObject(FIXTURE_DOCUMENT_TEMPLATES_VARIABLES)
    })

    // computed
    test('templateTitle', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.templateTitle).toBeUndefined()

      const template = FIXTURE_DOCUMENT_TEMPLATES[0]
      wrapper.setProps({
        value: {
          template: {
            title: template.title
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.templateTitle).toEqual(template.title)
    })

    test('attachment', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.attachment).toBeUndefined()

      const src = 'https://domain.com/item.pdf'
      wrapper.setProps({
        value: {
          file: {
            src
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.attachment).toEqual(src)
    })

    test('ownerName', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.ownerName).toBeUndefined()

      wrapper.setProps({
        value: {
          assigned: {
            name: 'Assigned name'
          }
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.ownerName).toEqual('Assigned name')
    })

    test('downloadable', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.downloadable).toBe(false)

      wrapper.setProps({
        value: {
          template_uuid: 'abc123'
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.downloadable).toBe(true)
    })

    test('templatesByUuid', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.templatesByUuid).toMatchObject({})

      wrapper.setProps({
        value: {
          templates: FIXTURE_DOCUMENT_TEMPLATES
        }
      })
      await wrapper.vm.$nextTick()
      const expected = FIXTURE_DOCUMENT_TEMPLATES.reduce((map, template) => {
        map[template.uuid] = template
        return map
      }, {})
      expect(wrapper.vm.templatesByUuid).toMatchObject(expected)
    })

    test('selectedTemplate', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.selectedTemplate).toBeUndefined()

      wrapper.setProps({
        value: {
          template_uuid: FIXTURE_DOCUMENT_TEMPLATES[0].uuid,
          templates: FIXTURE_DOCUMENT_TEMPLATES
        }
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.selectedTemplate).toMatchObject(FIXTURE_DOCUMENT_TEMPLATES[0])
    })

    test('content', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.content).toEqual('')

      wrapper.setProps({
        value: {
          template_uuid: FIXTURE_DOCUMENT_TEMPLATES[0].uuid,
          templates: FIXTURE_DOCUMENT_TEMPLATES,
          variables: FIXTURE_DOCUMENT_TEMPLATES_VARIABLES
        }
      })
      await wrapper.vm.$nextTick()
      const expected = Mustache.render(FIXTURE_DOCUMENT_TEMPLATES[0].content, FIXTURE_DOCUMENT_TEMPLATES_VARIABLES)
      expect(wrapper.vm.content).toEqual(expected)
    })

    test('isScrollVisible', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isScrollVisible).toEqual(true)

      wrapper.setProps({
        value: {
          template_uuid: FIXTURE_DOCUMENT_TEMPLATES[0].uuid
        },
        templates: FIXTURE_DOCUMENT_TEMPLATES,
        variables: FIXTURE_DOCUMENT_TEMPLATES_VARIABLES,
        previewDialog: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.isScrollVisible).toEqual(true)
    })
  })

  describe('Methods', () => {
    test('onClose()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.onClose()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().close
      expect(emitted).toBeTruthy()
    })

    test('acceptContract()', async () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.acceptContract()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted('contract-accepted')
      expect(emitted).toBeTruthy()
    })
  })
})
