import {
  App,
  createStore
} from 'fresh-bus'
import NotFoundPage from 'fresh-bus/pages/404.vue'
import page from '~/store/modules/page.js'

const initialState = {
  loginSuccessRedirectPath: '/admin',
  ...window.__INITIAL_STATE__
}
const appInstance = new App({
  components: {},
  store: createStore({
    navigation: {
      title: 'Food Fleet',
      logo: '/images/logo.png',
      drawerItems: [
        {
          action: 'icon-dashboard',
          title: 'Dashboard',
          to: '/admin'
        },
        {
          action: 'icon-users',
          title: 'Users',
          to: '/admin/users'
        },
        {
          action: 'icon-companies',
          title: 'Companies',
          to: '/admin/companies'
        },
        {
          action: 'icon-trucks',
          title: 'Fleet Members',
          to: '/admin/fleet-members'
        },
        {
          action: 'icon-venues',
          title: 'Venues',
          to: '/admin/venues'
        },
        {
          action: 'icon-events',
          title: 'Events',
          to: '/admin/events'
        },
        {
          action: 'icon-financial',
          title: 'Financials',
          to: '/admin/financials'
        },
        {
          action: 'icon-reports',
          title: 'Analytics',
          to: '/admin/analytics'
        }
      ],
      items: []
    },
    navigationAdmin: {
      title: 'Food Fleet',
      logo: '/images/logo.png',
      items: [
        {
          title: 'Dashboard',
          to: '/admin'
        },
        {
          title: 'Users',
          to: '/admin/users'
        },
        {
          title: 'Companies',
          to: '/admin/companies'
        },
        {
          title: 'Fleet Members',
          to: '/admin/fleet-members'
        },
        {
          title: 'Events',
          to: '/admin/events'
        },
        {
          title: 'Venues',
          to: '/admin/venues'
        },
        {
          title: 'Documents',
          to: '/admin/docs'
        },
        {
          title: 'Doc. Templates',
          to: '/admin/doc-templates'
        },
        {
          title: 'Financials',
          to: '/admin/financials'
        },
        {
          title: 'Analytics',
          to: '/admin/analytics'
        }
      ]
    },
    ...initialState
  },
  {
    modules: {
      page: page({})
    }
  }),
  theme: {
    primary: '#508c85',
    secondary: '#d0883d',
    accent: '#888888',
    inputaccent: '#E4EDEC',
    error: '#d94545',
    info: '#2196F3',
    success: '#71b179',
    warning: '#f9ad36'
  },

  // Page Not Found Configuration
  redirectOnNotFound: false,
  NotFoundPage,

  // Pages passed here will come before fresh-bus pages, therefore will be given first change to match routes
  pages: require.context('./pages', true, /\.vue$/),
  layouts: require.context('./layouts', false, /\.vue$/)
})

appInstance.getRouter().addRoutes([ { path: '/', redirect: '/admin' }, { path: '', redirect: '/admin' } ])

// We may consider only exposing the app when a certain key is set (true EXPOSE_APP=true)
window.__APP__ = appInstance
export default appInstance
