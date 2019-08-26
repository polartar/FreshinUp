import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/reportables/ReportableList.vue'
import { FIXTURE_REPORTABLES } from 'tests/__data__/reportables'
import { FIXTURE_PAYMENT_TYPES } from 'tests/__data__/paymentTypes'

describe('ReportableList', () => {
  // Component instance "under test"
  let localVue
  describe('Snapshots', () => {
    test('snapshot', () => {
      localVue = createLocalVue()
      const wrapper = mount(Component, {
        localVue: localVue,
        propsData: {
          reportables: FIXTURE_REPORTABLES,
          selectables: {
            'payment_types': FIXTURE_PAYMENT_TYPES
          }
        }
      })
      expect(wrapper.element).toMatchSnapshot()
    })
  })
  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })
    test('deleteReportable() emits delete', () => {
      const wrapper = shallowMount(Component)
      wrapper.vm.deleteReportable({ id: 8 })
      expect(wrapper.emitted().delete).toBeTruthy()
      expect(wrapper.emitted().delete).toHaveLength(1)
      expect(wrapper.emitted().delete[0]).toEqual([{ id: 8 }])
    })
    test('deleteReportables() emits deleteMultiple', () => {
      const wrapper = shallowMount(Component, {
        data: function () {
          return {
            selected: [1, 2]
          }
        }
      })
      wrapper.vm.deleteReportables()
      expect(wrapper.emitted().deleteMultiple).toBeTruthy()
      expect(wrapper.emitted().deleteMultiple).toHaveLength(1)
      expect(wrapper.emitted().deleteMultiple[0]).toEqual([[1, 2]])
    })
    test('reportLink(filters) with modifier null', () => {
      const component = shallowMount(Component, {
        propsData: {
          baseUrl: '/admin/reportables',
          reportables: [FIXTURE_REPORTABLES[0]],
          selectables: {
            'payment_types': FIXTURE_PAYMENT_TYPES
          }
        },
        localVue
      })
      expect(component.vm.reportLink(FIXTURE_REPORTABLES[0])).toEqual('/admin/reportables?fleet_member_uuid=1&date_after=2019-01-01&date_before=2019-06-01')
    })
    test('reportLink(filters)', () => {
      const component = shallowMount(Component, {
        propsData: {
          baseUrl: '/admin/reportables',
          reportables: [FIXTURE_REPORTABLES[0]],
          selectables: {
            'payment_types': FIXTURE_PAYMENT_TYPES
          }
        },
        localVue
      })
      let modifier1 = []
      modifier1[FIXTURE_REPORTABLES[0].id] = 1
      component.setData({ modifier_1: modifier1 })
      expect(component.vm.reportLink(FIXTURE_REPORTABLES[0])).toEqual('/admin/reportables?event_uuid=1&fleet_member_uuid=1&date_after=2019-01-01&date_before=2019-06-01')
    })
    test('changeModifier1Value(value, report)', () => {
      const component = shallowMount(Component, {
        propsData: {
          baseUrl: '/admin/reportables',
          reportables: [FIXTURE_REPORTABLES[0]],
          selectables: {
            'payment_types': FIXTURE_PAYMENT_TYPES
          }
        },
        localVue
      })
      let modifier1 = []
      modifier1[FIXTURE_REPORTABLES[0].id] = 1
      component.setData({ modifier_1: modifier1 })
      component.vm.changeModifier1Value(2, FIXTURE_REPORTABLES[0])
      expect(component.vm.modifier_1[FIXTURE_REPORTABLES[0].id]).toEqual(2)
    })
    test('changeModifier2Value(value, report)', () => {
      const component = shallowMount(Component, {
        propsData: {
          baseUrl: '/admin/reportables',
          reportables: [FIXTURE_REPORTABLES[0]],
          selectables: {
            'payment_types': FIXTURE_PAYMENT_TYPES
          }
        },
        localVue
      })
      let modifier2 = []
      modifier2[FIXTURE_REPORTABLES[0].id] = 1
      component.setData({ modifier_1: modifier2 })
      component.vm.changeModifier2Value(2, FIXTURE_REPORTABLES[0])
      expect(component.vm.modifier_2[FIXTURE_REPORTABLES[0].id]).toEqual(2)
    })
    test('formatFilters(filters)', () => {
      expect(Component.methods.formatFilters(FIXTURE_REPORTABLES[0].filters)).toEqual('Fleet Member, Date After, Date Before')
    })
  })
})
