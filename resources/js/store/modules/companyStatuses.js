import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore(
    'companyStatuses',
    { items, item },
    {
      itemsPath: () => `/foodfleet/company/statuses`,
    }
  )
  return {
    ...store,
    namespaced: true,
  }
}
