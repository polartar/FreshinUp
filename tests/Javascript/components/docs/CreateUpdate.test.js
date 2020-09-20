import { mount, shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import createLocalVue from 'vue-cli-plugin-freshinup-ui/utils/testing/createLocalVue'
import { FIXTURE_DOCUMENT, EMPTY_DOCUMENT } from 'tests/__data__/documents'
import { FIXTURE_DOCUMENT_TYPES } from 'tests/__data__/documentTypes'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'
import BaseComponent from '~/components/docs/CreateUpdate.vue'
import documents from '~/store/modules/documents'
import documentStatuses from '~/store/modules/documentStatuses'
import documentTypes from '~/store/modules/documentTypes'

describe('Document CreateUpdate Component', () => {
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
      store = createStore(
        {},
        {
          modules: {
            documents: documents({}),
            documentStatuses: documentStatuses({}),
            documentTypes: documentTypes({})
          }
        }
      )
    })

    afterEach(() => {
      mock.restore()
    })

    it('mount create page', async () => {
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

    it('mount edit page', async () => {
      const Component = {
        extends: BaseComponent,
        layout: BaseComponent.layout,
        beforeRouteEnterOrUpdate: BaseComponent.beforeRouteEnterOrUpdate
      }
      const wrapper = mount(Component, {
        localVue,
        store
      })
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
      store = createStore(
        {},
        {
          modules: {
            documents: documents({}),
            documentStatuses: documentStatuses({}),
            documentTypes: documentTypes({})
          }
        }
      )
    })

    afterEach(() => {
      mock.restore()
    })

    test('changeBasicInfo function change doc', async () => {
      const wrapper = shallowMount(BaseComponent, {
        localVue: localVue,
        store
      })

      await wrapper.vm.$store.dispatch('documents/getItem', {
        params: { id: 1 }
      })

      wrapper.vm.changeBasicInfo({
        title: 'mock title123'
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.title).toBe('mock title123')
    })

    test('changePublishing function change doc', async () => {
      const wrapper = shallowMount(BaseComponent, {
        localVue: localVue,
        store
      })

      await wrapper.vm.$store.dispatch('documents/getItem', {
        params: { id: 1 }
      })

      wrapper.vm.changePublishing({
        assigned_type: 3,
        assigned_uuid: 'mock-uuid'
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.doc.assigned_type).toBe(3)
      expect(wrapper.vm.doc.assigned_uuid).toBe('mock-uuid')
    })
  })
})
