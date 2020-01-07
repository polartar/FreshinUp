import { shallowMount } from '@vue/test-utils'
import { createStore } from 'fresh-bus/store'
import { createLocalVue } from 'fresh-bus/tests/utils'
import { FIXTURE_DOCUMENTS_RESPONSE } from 'tests/__data__/documents'
import documents from '~/store/modules/documents'

export const docMethodsTests = function (Component) {
  let localVue, mock, store, actions
  beforeEach(() => {
    const vue = createLocalVue({ validation: true })
    const docModule = documents({})
    localVue = vue.localVue
    actions = {
      patchItem: jest.fn()
    }
    store = createStore(
      {
        docs: {
          items: FIXTURE_DOCUMENTS_RESPONSE
        }
      },
      {
        modules: {
          documents: {
            ...docModule,
            actions: { ...docModule.actions, ...actions }
          }
        }
      }
    )
    mock = vue.mock
  })

  afterEach(() => {
    mock.restore()
  })

  test('docsChangeStatus function change doc status', async () => {
    const wrapper = shallowMount(Component, {
      localVue: localVue,
      store
    })

    wrapper.vm.docsChangeStatus(2, { uuid: 'mock uuid' })
    const data = { data: { status: 2 }, params: { id: 'mock uuid' } }
    expect(actions.patchItem).toHaveBeenCalled()
    expect(actions.patchItem.mock.calls).toHaveLength(1)
    expect(actions.patchItem.mock.calls[0][1]).toEqual(data)
  })

  test('docsChangeStatusMultiple function change doc status for each', async () => {
    const wrapper = shallowMount(Component, {
      localVue: localVue,
      store
    })

    wrapper.vm.docsChangeStatusMultiple(3, [
      { uuid: 'mock uuid 1' },
      { uuid: 'mock uuid 2' }
    ])

    expect(actions.patchItem).toHaveBeenCalled()
    expect(actions.patchItem.mock.calls).toHaveLength(2)

    const firstData = {
      data: { status: 3 },
      params: { id: 'mock uuid 1' }
    }
    const secondData = {
      data: { status: 3 },
      params: { id: 'mock uuid 2' }
    }

    expect(actions.patchItem.mock.calls[0][1]).toEqual(firstData)
    expect(actions.patchItem.mock.calls[1][1]).toEqual(secondData)
  })

  test('docsOnPaginate function change paginate', () => {
    const wrapper = shallowMount(Component, {
      localVue: localVue,
      store
    })

    wrapper.vm.docsOnPaginate({
      rowsPerPage: 2,
      totalItems: 5,
      page: 2
    })
    expect(wrapper.vm.docsPagination.rowsPerPage).toBe(2)
  })

  test('docsDelete function change deleteTemp', () => {
    const wrapper = shallowMount(Component, {
      localVue: localVue,
      store
    })

    wrapper.vm.docsDelete({ id: 1 })
    expect(wrapper.vm.docsDeleteTemp[0].id).toBe(1)
    expect(wrapper.vm.docsDeleteDialog).toBeTruthy()

    wrapper.vm.docsDelete([{ id: 1 }])
    expect(wrapper.vm.docsDeleteTemp[0].id).toBe(1)
    expect(wrapper.vm.docsDeleteDialog).toBeTruthy()
  })
}
