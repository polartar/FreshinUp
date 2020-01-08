import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/Customers.vue'
import { FIXTURE_CUSTOMERS } from 'tests/__data__/customers'
import { FIXTURE_EVENT_STATUSES } from 'tests/__data__/eventStatuses'

describe('Customers component', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('customers assigned', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          customers: FIXTURE_CUSTOMERS
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('customers empty', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          statuses: FIXTURE_EVENT_STATUSES,
          customers: []
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('view details', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.viewDetails(FIXTURE_CUSTOMERS[0])

      expect(wrapper.emitted()['manage-view-details']).toBeTruthy()

      expect(wrapper.emitted()['manage-view-details'][0][0]).toEqual(FIXTURE_CUSTOMERS[0])
    })
  })
})
