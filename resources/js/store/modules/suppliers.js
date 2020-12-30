import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'suppliers',
    { items, item },
    {
      itemsPath: () => `/foodfleet/suppliers`
    }
  )

  const storeModule = makeRestStore(
    'stores',
    { item },
    {
      itemsPath: ({ supplierId }) => `/foodfleet/suppliers/${supplierId}/stores`
    }
  )

  return {
    namespaced: true,
    ...store,
    modules: {
      stores: {
        ...storeModule,
        modules: {
          ...storeModule.modules,
          stats: makeRestStore(
            'stats',
            { item },
            {
              itemsPath: ({ supplierId }) => `/foodfleet/suppliers/${supplierId}/stores/stats`
            }
          )
        }
      },
      documents: makeRestStore(
        'documents',
        { item },
        {
          itemsPath: ({ supplierId }) => `/foodfleet/suppliers/${supplierId}/documents`
        }
      ),
      events: makeRestStore(
        'events',
        { item },
        {
          itemsPath: ({ supplierId }) => `/foodfleet/suppliers/${supplierId}/events`
        }
      )
    }
  }
}
