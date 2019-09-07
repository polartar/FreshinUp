import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

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
