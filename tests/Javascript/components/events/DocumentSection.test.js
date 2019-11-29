import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/DocumentSection'
import { FIXTURE_DOCUMENTS } from 'tests/__data__/documents'
import { FIXTURE_DOCUMENT_STATUSES } from 'tests/__data__/documentStatuses'

describe('Document list component', () => {
  let localVue

  test('Snapshots', () => {
    localVue = createLocalVue()
    const wrapper = mount(Component, {
      localVue: localVue,
      propsData: {
        statuses: FIXTURE_DOCUMENT_STATUSES,
        documents: FIXTURE_DOCUMENTS
      }
    })
    expect(wrapper.element).toMatchSnapshot()
  })

  test('document list is empty', () => {
    localVue = createLocalVue()
    const wrapper = mount(Component, {
      localVue: localVue,
      propsData: {
        statuses: FIXTURE_DOCUMENT_STATUSES,
        documents: []
      }
    })
    expect(wrapper.element).toMatchSnapshot()
  })

  describe('Methods', () => {
    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('changeStatus function emitted change-status action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      const mockDoc = {
        id: 1, title: 'mock title', status: 1
      }

      wrapper.vm.changeStatus(2, mockDoc)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('createNewDoc function will redirect to the create new doc page', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.$router = {
        push: jest.fn()
      }

      wrapper.vm.createNewDoc()

      expect(wrapper.vm.$router.push).toHaveBeenCalledWith({ 'path': '/admin/docs/new' })
    })

    test('viewDetails function will redirect to the document details page', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.$router = {
        push: jest.fn()
      }

      const mockDocUUID = 'f5777e5d-4ee4-4df3-abca-2dae0fb90b42'

      wrapper.vm.viewDetails(mockDocUUID)

      expect(wrapper.vm.$router.push).toHaveBeenCalledWith({ 'path': `/admin/docs/${mockDocUUID}` })
    })
  })
})
