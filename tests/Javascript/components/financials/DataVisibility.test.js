import { shallowMount, mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import Component from '~/components/financials/DataVisibility.vue'

jest.useFakeTimers()

const visibleParameters = [
  'event_location',
  'square_created_at',
  'total_money',
  'total_tax_money',
  'total_discount_money'
]

const parameters = [
  { name: 'event_location', label: 'Event / Location' },
  { name: 'square_created_at', label: 'Creation date' },
  { name: 'total_money', label: 'Total' },
  { name: 'total_tax_money', label: 'Tax total' },
  { name: 'total_discount_money', label: 'Total discount' },
  { name: 'total_service_charge_money', label: 'Total Service Charge' },
  { name: 'square_updated_at', label: 'Update date' },
  { name: 'items', label: 'Items' },
  { name: 'square_id', label: 'Reference ID' }
]

describe('DataVisibility', () => {
  // Component instance "under test"
  let localVue
  beforeEach(() => {
    const vue = createLocalVue()
    localVue = vue.localVue
  })
  describe('Snapshots', () => {
    test('parameters and visibleParameters set', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        },
        stubs: {
          draggable: true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
    test('maxWidthBottomRow set', () => {
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters,
          maxWidthBottomRow: 200
        },
        stubs: {
          draggable: true
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Computed', () => {
    test('parameterSelectables', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        },
        stubs: {
          draggable: true
        }
      })
      expect(wrapper.vm.parameterSelectables).toEqual([
        { 'id': 'total_service_charge_money', 'name': 'Total Service Charge' },
        { 'id': 'square_updated_at', 'name': 'Update date' },
        { 'id': 'items', 'name': 'Items' },
        { 'id': 'square_id', 'name': 'Reference ID' }
      ])
    })
    test('width', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        },
        stubs: {
          draggable: true
        }
      })
      expect(wrapper.vm.width).toEqual({ 'width': '422px' })
    })
  })
  describe('Methods', () => {
    test('close() emits close', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        }
      })
      wrapper.vm.close()
      expect(wrapper.emitted().close).toBeTruthy()
    })
    test('clearAll() reset visible_parameters array', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        }
      })
      expect(wrapper.vm.visible_parameters).toEqual(visibleParameters)
      wrapper.vm.clearAll()
      expect(wrapper.vm.visible_parameters).toEqual([])
    })
    test('save() emits save', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        }
      })
      wrapper.vm.save()
      expect(wrapper.emitted().save).toBeTruthy()
      expect(wrapper.emitted().save).toHaveLength(1)
      expect(wrapper.emitted().save[0]).toEqual([visibleParameters])
    })
    test('getParamLabel() return label value for specified param', () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        propsData: {
          visibleParameters: visibleParameters,
          parameters: parameters
        }
      })
      expect(wrapper.vm.getParamLabel('square_created_at')).toEqual('Creation date')
    })
  })
})
