import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/reportables/SaveSearch.vue'
import { FIXTURE_MODIFIER_AUTOCOMPLETE, FIXTURE_MODIFIER_SELECT, FIXTURE_MODIFIER_DATE, FIXTURE_MODIFIER_TEXT } from 'tests/__data__/modifiers'

const modifiers = [
  FIXTURE_MODIFIER_AUTOCOMPLETE,
  FIXTURE_MODIFIER_SELECT,
  FIXTURE_MODIFIER_DATE,
  FIXTURE_MODIFIER_TEXT
]

describe('SaveSearch', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('modifiers set', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          modifiers: modifiers
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('is platform admin', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          modifiers: modifiers,
          isPlatformAdmin: true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('close() emits close', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          modifiers: modifiers
        }
      })
      wrapper.vm.close()
      expect(wrapper.emitted().close).toBeTruthy()
    })
    test('save() emits save with value', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          modifiers: modifiers
        }
      })
      wrapper.vm.search = {
        name: 'test',
        modifier_1_id: 1,
        modifier_2_id: 2,
        featured: false
      }
      wrapper.vm.save()
      expect(wrapper.emitted().save).toBeTruthy()
      expect(wrapper.emitted().save).toHaveLength(1)
      expect(wrapper.emitted().save[0]).toEqual([{
        name: 'test',
        modifier_1_id: 1,
        modifier_2_id: 2,
        featured: false
      }])
    })
  })
  describe('Computed', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })
    test('modifierSelectables', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          modifiers: modifiers
        }
      })
      expect(wrapper.vm.modifierSelectables).toEqual([
        { 'text': 'None selected', 'value': null },
        { 'text': 'Event', 'value': 1 },
        { 'text': 'Payment type', 'value': 2 },
        { 'text': 'Min Date', 'value': 3 },
        { 'text': 'Min price', 'value': 4 }
      ])
    })
  })
})
