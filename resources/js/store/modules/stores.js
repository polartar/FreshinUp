import makeRestStore, {
  buildApi,
  makeModule
} from 'fresh-bus/store/utils/makeRestStore'

export default ({ items, item }) => {
  const storesApi = buildApi('foodfleet/stores', { items, item })
  const store = makeModule(storesApi.getStore(), 'stores')

  const sortables = [
    { value: '-created_at', text: 'Newest' },
    { value: 'created_at', text: 'Oldest' },
    { value: 'name', text: 'Name (A - Z)' },
    { value: '-name', text: 'Name (Z - A)' }
  ]

  // Initial State
  store.state = {
    ...store.state,
    sortables
  }

  return {
    namespaced: true,
    ...store,
    modules: {
      summary: makeRestStore(
        'summary',
        { item },
        {
          itemPath: ({ id }) => `/foodfleet/store-summary/${id}`
        }
      ),
      serviceSummary: makeRestStore(
        'serviceSummary',
        { item },
        {
          itemPath: ({ id }) => `/foodfleet/store-service-summary/${id}`
        }
      )
    }
  }
}
