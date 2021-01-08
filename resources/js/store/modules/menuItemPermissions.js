import makeRestStore from '@freshinup/core-ui/src/store/utils/makeRestStore'
import {
  enabledFields,
  readonlyFields,
  validationRules,
  labels
} from '@freshinup/core-ui/src/store/utils/permissionsHelpers'

export default ({ items, item }) => {
  const store = makeRestStore(
    'menuItemPermissions',
    { items, item },
    {
      itemsPath: () => `/foodfleet/permissions/menu-items`
    }
  )

  // Add getters
  store.getters = {
    ...store.getters,
    enabledFields,
    readonlyFields,
    validationRules,
    labels
  }

  return {
    namespaced: true,
    ...store
  }
}
