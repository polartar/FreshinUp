import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'stores',
    { items, item },
    {
      itemsPath: () => `/foodfleet/stores`,
      itemPath: ({ id }) => `/foodfleet/stores/${id}`
    }
  )

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
