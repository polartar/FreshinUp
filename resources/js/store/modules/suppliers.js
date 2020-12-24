import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'supplier',
    { items, item },
    {
      itemsPath: () => `/foodfleet/supplier`
    }
  )

  return {
    namespaced: true,
    ...store,
    modules: {
      summary: makeRestStore(
        'stores',
        { item },
        {
          itemPath: ({ supplier }) => `/foodfleet/supplier/${supplier}/stores`
        }
      ),
      serviceSummary: makeRestStore(
        'documents',
        { item },
        {
          itemPath: ({ supplier }) => `/foodfleet/supplier/${supplier}/documents`
        }
      ),
      events: makeRestStore(
        'events',
        { item },
        {
          itemsPath: ({ supplier }) => `/foodfleet/supplier/${supplier}/events`
        }
      )
    }
  }
}
