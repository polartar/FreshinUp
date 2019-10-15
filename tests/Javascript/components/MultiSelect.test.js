import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'
import Component from '~/components/MultiSelect.vue'

const allSelected = FIXTURE_EVENT_STATUSES.map(item => item.id)

describe('MultiSelect', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('defaults', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          value: [],
          items: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('toggle function select all', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: [],
          items: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.vm.toggle()
      wrapper.vm.$nextTick(() => {
        expect(wrapper.emitted()['input']).toBeTruthy()
        expect(wrapper.emitted()['input'][0][0]).toEqual(FIXTURE_EVENT_STATUSES)
      })
    })

    test('toggle function clear all', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: allSelected,
          items: FIXTURE_EVENT_STATUSES
        }
      })
      wrapper.vm.toggle()
      wrapper.vm.$nextTick(() => {
        expect(wrapper.emitted()['input']).toBeTruthy()
        expect(wrapper.emitted()['input'][0][0]).toEqual([])
      })
    })
  })

  describe('Computed', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('selectAll', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: allSelected,
          items: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.vm.selectAll).toBeTruthy()
      expect(wrapper.vm.selectSome).toBeFalsy()
    })
    test('selectSome', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: [ 1, 2 ],
          items: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.vm.selectAll).toBeFalsy()
      expect(wrapper.vm.selectSome).toBeTruthy()
    })
    test('icon for no select', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: [],
          items: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.vm.icon).toBe('far fa-square')
    })
    test('icon for some select', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: [ 1, 2 ],
          items: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.vm.icon).toBe('fa-minus-square')
    })
    test('icon for select all', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          value: allSelected,
          items: FIXTURE_EVENT_STATUSES
        }
      })
      expect(wrapper.vm.icon).toBe('fa-check-square')
    })
  })
})
