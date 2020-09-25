import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'

export default ({ items, item }) => {
  const store = makeRestStore('foodfleet/locations', { items, item })
  return {
    namespaced: true,
    ...store
  }
}
