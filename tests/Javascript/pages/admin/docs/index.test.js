import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import {
  FIXTURE_DOCUMENTS_RESPONSE,
  FIXTURE_DOCUMENTS_SORTED_BY_FIRSTNAME
} from 'tests/__data__/documents'
import Component from '~/pages/admin/docs/index.vue'
import documents from '~/store/modules/documents'
import documentStatuses from '~/store/modules/documentStatuses'
import documentTypes from '~/store/modules/documentTypes'
import { docMethodsTests } from 'tests/shared/doc_management_tests.js'

describe('Admin Docs Page', () => {
  let localVue, mock
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
    })

    afterEach(() => {
      mock.restore()
    })

    test('snapshot', async () => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
        .onGet('api/foodfleet/documents', {
          params: { 'page[size]': 10, 'page[number]': 1, sort: 'first_name' }
        })
        .reply(200, { data: FIXTURE_DOCUMENTS_SORTED_BY_FIRSTNAME })
        .onGet('api/foodfleet/documents')
        .reply(200, FIXTURE_DOCUMENTS_RESPONSE)
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      const store = createStore(
        {},
        {
          modules: {
            documents: documents({}),
            documentStatuses: documentStatuses({}),
            documentTypes: documentTypes({})
          }
        }
      )
      const wrapper = shallowMount(Component, {
        localVue: localVue,
        store
      })
      // Action: change State Machine's state
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      await wrapper.vm.$store.dispatch('documents/getItems')
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Doc Methods', () => {
    docMethodsTests(Component)
  })
})
