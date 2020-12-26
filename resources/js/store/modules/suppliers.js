import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'suppliers',
    { items, item },
    {
      itemsPath: () => `/foodfleet/suppliers`
    }
  )

  return {
    namespaced: true,
    ...store,
    modules: {
      stores: makeRestStore(
        'stores',
        { item },
        {
          itemPath: ({ supplierId }) => `/foodfleet/suppliers/${supplierId}/stores`
        }
      ),
      documents: makeRestStore(
        'documents',
        { item },
        {
          itemPath: ({ supplierId }) => `/foodfleet/suppliers/${supplierId}/documents`
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
