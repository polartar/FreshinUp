import * as Stories from './BasicInformation.stories'
import Component, {EDITOR_OPTIONS} from './BasicInformation'
import { shallowMount } from '@vue/test-utils'
import { FIXTURE_USERS } from '../../../../tests/Javascript/__data__/users'
import { FIXTURE_DOCUMENT_TEMPLATES } from '../../../../tests/Javascript/__data__/documentTemplates'
import { FIXTURE_DOCUMENT_TEMPLATE_STATUSES } from '../../../../tests/Javascript/__data__/documentTemplateStatuses'
describe('component/doc-templates/BasicInformation', () => {
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
      expect(wrapper.vm.value).toMatchObject(DEFAULT_TEMPLATE)

      const template = FIXTURE_DOCUMENT_TEMPLATES[0]
      wrapper.setProps({
        value: template
      })
      expect(wrapper.vm.value).toMatchObject(template)
    })
    test('editorOptions', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.editorOptions).toMatchObject(EDITOR_OPTIONS)
    })
    test('isNew', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.isNew).toBe(false)

      wrapper.setProps({
        value: {
          uuid: 'abc123'
        }
      })
      expect(wrapper.vm.isNew).toBe(true)
    })
    test('statuses', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.statuses).toHaveLength(0)

      wrapper.setProps({
        statuses: FIXTURE_DOCUMENT_TEMPLATE_STATUSES
      })
      expect(wrapper.vm.statuses).toMatchObject(FIXTURE_DOCUMENT_TEMPLATE_STATUSES)
    })
    test('updaterName', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.updaterName).toBeNull()

      const user = FIXTURE_USERS[0]
      wrapper.setProps({
        value: {
          updated_by: user
        }
      })
      expect(wrapper.vm.updaterName).toEqual(user.name)
    })
  })

  describe('Methods', () => {
    test('deleteItem()', async () => {
      const template = FIXTURE_DOCUMENT_TEMPLATES[0]
      const wrapper = shallowMount(Component, {
        propsData: {
          value: template
        }
      })
      wrapper.vm.deleteItem()
      const event = wrapper.emitted().delete
      expect(event).toBeTruthy()
      expect(event[0][0]).toMatchObject(template)
    })
  })
})
