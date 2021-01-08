import busCompanies from 'fresh-bus/store/modules/companies'

export default ({ items, item }) => {
  const store = busCompanies({ items, item })
  return {
    ...store,
    namespaced: true
  }
}
