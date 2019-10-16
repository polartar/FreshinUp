import { mount } from '@vue/test-utils'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_COMPANIES_RESPONSE } from 'tests/__data__/companies'
import Component from '~/components/datatable/CompaniesList.vue'

describe('Companies List Component', () => {
  let localVue

  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
    })

    test('snapshot', () => {
      const wrapper = mount(Component, {
        localVue,
        propsData: {
          companies: FIXTURE_COMPANIES_RESPONSE.data
        }
      })

      expect(wrapper.element).toMatchSnapshot()
    })

    test('headers', () => {
      const wrapper = mount(Component, {
        localVue,
        propsData: {
          companies: FIXTURE_COMPANIES_RESPONSE.data
        }
      })

      expect(wrapper.findAll('thead th').at(1).text()).toContain('Status')
      expect(wrapper.findAll('thead th').at(2).text()).toContain('Name')
      expect(wrapper.findAll('thead th').at(3).text()).toContain('Company type')
      expect(wrapper.findAll('thead th').at(4).text()).toContain('Members')
      expect(wrapper.findAll('thead th').at(5).text()).toContain('Manage')
    })

    test('content', () => {
      const wrapper = mount(Component, {
        localVue,
        propsData: {
          companies: FIXTURE_COMPANIES_RESPONSE.data
        }
      })

      expect(wrapper.findAll('tbody tr').at(0).text()).toContain(FIXTURE_COMPANIES_RESPONSE.data[0].name)
      expect(wrapper.findAll('tbody tr').at(1).text()).toContain(FIXTURE_COMPANIES_RESPONSE.data[1].name)
    })
  })
})
