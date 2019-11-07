import { createLocalVue, mount } from '@vue/test-utils'
import Component from '~/components/events/StoreFilter.vue'
import { FIXTURE_EVENTS_FLEET_MEMBER } from 'tests/__data__/eventsStore'

describe('event Store Filter Sorter component', () => {
  let localVue
  describe('Snapshots', () => {
    test('types set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: { tags: FIXTURE_EVENTS_FLEET_MEMBER['types'] }
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })

    test('types empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: { tags: [] }
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Action', () => {
    test('clearFilters function clear filters', () => {
      const localVue = createLocalVue()

      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: { tags: FIXTURE_EVENTS_FLEET_MEMBER['types'] }
        }
      })
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.filters.location).toBeNull()
      expect(wrapper.vm.filters.type).toBeNull()
      expect(wrapper.vm.filters.tag).toBeNull()
    })
  })
})