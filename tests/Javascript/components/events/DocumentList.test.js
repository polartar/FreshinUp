import { createLocalVue, shallowMount, mount } from '@vue/test-utils'
import Component from '~/components/events/DocumentList'
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

      const mockEvent = {
        id: 1, title: 'mock title', status: 1
      }

      wrapper.vm.changeStatus(2, mockEvent)

      expect(wrapper.emitted()['change-status']).toBeTruthy()
      expect(wrapper.emitted()['change-status'][0][0]).toEqual(2)
    })

    test('viewDetails function emitted view-details action', () => {
      const wrapper = shallowMount(Component, {
        localVue
      })

      wrapper.vm.viewDetails('view-details-url')

      expect(wrapper.emitted()['view-details']).toBeTruthy()
      expect(wrapper.emitted()['view-details'][0][0]).toEqual('view-details-url')
    })
  })
})
