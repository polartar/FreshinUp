import { createLocalVue, mount } from '@vue/test-utils'
import Component from '~/components/events/StoreFilter.vue'
import { FIXTURE_EVENTS_STORE } from 'tests/__data__/eventsStore'

describe('event Store Filter Sorter component', () => {
  let localVue
  describe('Snapshots', () => {
    test('types set', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {  },
          types: FIXTURE_EVENTS_STORE['types']
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })

    test('types empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {  },
          types: FIXTURE_EVENTS_STORE['types']
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
          filters: {  },
          types: FIXTURE_EVENTS_STORE['types']
        }
      })
      wrapper.vm.clearFilters({})
      expect(wrapper.vm.filters.location).toBeNull()
      expect(wrapper.vm.filters.type).toBeNull()
      expect(wrapper.vm.filters.tag).toBeNull()
    })

    test('run function emitted runFilter', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          filters: {
            location: 'South Abagail',
            type: 1,
            tags: [ { uuid: 3 } ]
          },
          types: FIXTURE_EVENTS_STORE['types']
        }
      })
      wrapper.vm.run({})
      expect(wrapper.emitted().runFilter).toBeTruthy()
      const runParams = wrapper.emitted().runFilter[0][0]
      console.log(runParams)
      expect(runParams['location']).toEqual("South Abagail")
      expect(runParams['type']).toEqual(1)
      expect(runParams['tags']).toEqual([ 3 ])
    })
  })
})