import { axios, providers } from './PageStoryInstances'
import { createStoreFromProviders } from '@freshinup/core-ui/src/App'

export default (initialState = {}, options = {}) => {
  const {
    navigationAdmin: navigationAdminState,
    userNotifications: notificationState,
    page: pageState,
    ...state } = initialState
  return createStoreFromProviders(
    providers,
    {
      navigationAdmin: {
        headerImage: '/images/img-header-background.png',
        ...navigationAdminState
      },
      page: {
        loadingColor: 'accent',
        ...pageState
      },
      userNotifications: {
        fetchInterval: 0,
        ...notificationState
      },
      ...state
    },
    {
      axios,
      ...options
    }
  )
}
