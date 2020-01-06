import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'paymenttypes',
    { item: initialState.item, items: initialState.items },
    {
      itemsPath: () => `/foodfleet/payment-types`,
      itemPath: ({ id }) => `/foodfleet/payment-types/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
