import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'financialreports',
    { item: initialState.item, items: initialState.items },
    {
      itemsPath: () => `/foodfleet/financial-reports`,
      itemPath: ({ id }) => `/foodfleet/financial-reports/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
