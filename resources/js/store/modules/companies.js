import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore('foodfleet/companies', { items, item })

  return {
    ...store,
    namespaced: true,
    modules: {
      squareLocations: makeRestStore(
        'squareLocations',
        { items, item },
        {
          itemsPath: ({ companyId }) => `/foodfleet/companies/${companyId}/square-locations`,
          itemPath: ({ companyId, id }) => `/foodfleet/companies/${companyId}/square-locations/${id}`
        }
      )
    }
  }
}
