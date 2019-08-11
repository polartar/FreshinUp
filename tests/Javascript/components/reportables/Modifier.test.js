import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/reportables/Modifier.vue'
import { FIXTURE_MODIFIER_AUTOCOMPLETE, FIXTURE_MODIFIER_SELECT, FIXTURE_MODIFIER_DATE, FIXTURE_MODIFIER_TEXT } from 'tests/__data__/modifiers'

describe('Modifier', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('autocomplete', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          modifier: FIXTURE_MODIFIER_AUTOCOMPLETE
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('select', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          modifier: FIXTURE_MODIFIER_SELECT
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('date', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          modifier: FIXTURE_MODIFIER_DATE
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('text', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          modifier: FIXTURE_MODIFIER_TEXT
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('selectValue() emits change', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectValue('test')
      expect(wrapper.emitted().change).toBeTruthy()
      expect(wrapper.emitted().change).toHaveLength(1)
      expect(wrapper.emitted().change[0]).toEqual(['test'])
    })
    test('selectAutocomplete() emits change with value', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectAutocomplete({ uuid: 17 })
      expect(wrapper.emitted().change).toBeTruthy()
      expect(wrapper.emitted().change).toHaveLength(1)
      expect(wrapper.emitted().change[0]).toEqual([17])
    })
    test('selectAutocomplete() emits change with null', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.selectAutocomplete(null)
      expect(wrapper.emitted().change).toBeTruthy()
      expect(wrapper.emitted().change).toHaveLength(1)
      expect(wrapper.emitted().change[0]).toEqual([null])
    })
  })
})
