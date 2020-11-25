import { mount } from '@vue/test-utils'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_DOCUMENT, EMPTY_DOCUMENT } from 'tests/__data__/documents'
import { FIXTURE_DOCUMENT_TYPES } from 'tests/__data__/documentTypes'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import BaseComponent from '~/components/docs/CreateUpdate.vue'
import createStore from 'tests/createStore'

describe('components/docs/CreateUpdate', () => {
  let localVue, mock, store
  describe('Mount', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/documents/1')
        .reply(200, { data: FIXTURE_DOCUMENT })
        .onGet('api/foodfleet/documents/new')
        .reply(200, { data: EMPTY_DOCUMENT })
        .onGet('api/foodfleet/document-types')
        .reply(200, { data: FIXTURE_DOCUMENT_TYPES })
        .onGet('api/foodfleet/document-statuses')
        .reply(200, { data: FIXTURE_DOCUMENT_STATUSES })
        .onGet('foodfleet/stores')
        .reply(200, {
          data: [
            { name: 'eligendi', uuid: '0623e163-d229-4fe9-b54f-6bbfd5b559e0' }
          ]
        })
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })

    it.skip('mount create page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate,
        data () {
          return {
            isNew: true
          }
        }
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      await wrapper.vm.$store.dispatch('documents/getItem', {
        params: { id: 'new' }
      })
      await wrapper.vm.$store.dispatch('documentStatuses/getItems')
      await wrapper.vm.$store.dispatch('documentTypes/getItems')
      await wrapper.vm.$store.dispatch('page/setLoading', false)
      await wrapper.vm.$nextTick()
      expect(wrapper.html()).toContain('New Document')
      expect(wrapper.html()).not.toContain(FIXTURE_DOCUMENT.owner.name)
      expect(wrapper.element).toMatchSnapshot()
    })

    it.skip('mount edit page', async (done) => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
      Component.beforeRouteEnterOrUpdate(wrapper.vm, null, { params: { id: 1 } }, async () => {
        await wrapper.vm.$store.dispatch('documents/getItem', {
          params: { id: 1 }
        })
        await wrapper.vm.$store.dispatch('documentStatuses/getItems')
        await wrapper.vm.$store.dispatch('documentTypes/getItems')
        await wrapper.vm.$store.dispatch('page/setLoading', false)
        await wrapper.vm.$nextTick()
        expect(wrapper.html()).toContain('Document Details')
        expect(wrapper.html()).toContain(FIXTURE_DOCUMENT.owner.name)
        expect(wrapper.element).toMatchSnapshot()
        done()
      })
    })
  })

  describe('Methods', () => {
    beforeEach(() => {
      const vue = createLocalVue({ validation: true })
      localVue = vue.localVue
      mock = vue.mock
      mock
        .onGet('api/foodfleet/documents/1')
        .reply(200, { data: FIXTURE_DOCUMENT })
        .onGet('api/foodfleet/documents/new')
        .reply(200, { data: EMPTY_DOCUMENT })
        .onGet('api/foodfleet/document-types')
        .reply(200, { data: FIXTURE_DOCUMENT_TYPES })
        .onGet('api/foodfleet/document-statuses')
        .reply(200, { data: FIXTURE_DOCUMENT_STATUSES })
        .onGet('foodfleet/stores')
        .reply(200, {
          data: [
            { name: 'eligendi', uuid: '0623e163-d229-4fe9-b54f-6bbfd5b559e0' }
          ]
        })
        .onAny()
        .reply(config => {
          console.warn('No mock match for ' + config.url, config)
          return [404, {}]
        })
      store = createStore()
    })

    afterEach(() => {
      mock.restore()
    })
  })
})
