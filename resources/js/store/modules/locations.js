import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('foodfleet/locations', { items, item })
}
