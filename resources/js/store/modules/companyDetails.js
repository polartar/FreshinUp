import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  return {
    ...makeRestStore('companyDetails', { items, item }),
    modules: {
      users: makeRestStore(
        'users',
        { items, item },
        {
          itemsPath: ({ companyId }) =>
            `/foodfleet/companies/${companyId}/members`,
          itemPath: ({ companyId }) =>
            `/foodfleet/companies/${companyId}/members`
        }
      )
    }
  }
}
