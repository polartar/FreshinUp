import makeRestStore from '../utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('foodfleet/venues', { items, item })
}
