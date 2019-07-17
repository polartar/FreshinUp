import {
  App,
  createStore
} from 'fresh-bus'

const initialState = window.__INITIAL_STATE__
const appInstance = new App({
  components: {},
  store: createStore(
    initialState
  ),
  theme: {
    primary: '#f37021',
    secondary: '#252525',
    accent: '#888888',
    inputaccent: '#E4EDEC',
    error: '#FF5252',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FFC107'
  }

  // Pages passed here will come before fresh-bus pages, therefore will be given first change to match routes
  // pages: require.context('./pages', true, /\.vue$/),
  // layouts: require.context('./layouts', false, /\.vue$/)
})

// We may consider only exposing the app when a certain key is set (true EXPOSE_APP=true)
window.__APP__ = appInstance
export default appInstance
