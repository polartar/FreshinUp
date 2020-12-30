import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'companyTypes',
    { items, item },
    {
      itemsPath: () => `/foodfleet/company/types`
    }
  )
  return {
    ...store,
    namespaced: true
  }
}
