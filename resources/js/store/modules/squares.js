import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default (initialState = {}) => {
  const store = makeRestStore(
    'squares',
    { ...initialState },
    {
      itemsPath: () => `/foodfleet/squares`,
      itemPath: ({ id }) => `/foodfleet/squares/${id}`
    }
  )

  return {
    namespaced: true,
    ...store
  }
}
