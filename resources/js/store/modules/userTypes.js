import busUserTypes from 'fresh-bus/store/modules/userTypes'

export const USER_TYPE = {
  SUPPLIER: 1,
  CUSTOMER: 2,
  FOOD_FLEET_MEMBER: 3
}
export default ({ items, item }) => {
  const store = busUserTypes({ items, item })
  return {
    ...store,
    namespaced: true,
    getters: {
      ...store.getters,
      USER_TYPE () {
        return USER_TYPE
      }
    }
  }
}
