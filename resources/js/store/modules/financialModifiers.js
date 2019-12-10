import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'financialmodifiers',
    { item: initialState.item, items: initialState.items },
    {
      itemsPath: () => `/foodfleet/financial-modifiers`,
      itemPath: ({ id }) => `/foodfleet/financial-modifiers/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
