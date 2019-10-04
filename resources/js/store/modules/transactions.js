import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'transactions',
    { item: initialState.item, items: initialState.items },
    {
      itemsPath: () => `/foodfleet/transactions`,
      itemPath: ({ id }) => `/foodfleet/transactions/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
