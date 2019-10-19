import makeRestStore from 'fresh-bus/store/utils/makeRestStore'

export default ({ items, item }) => {
  return makeRestStore('foodfleet/document-statuses', { items, item })
}
