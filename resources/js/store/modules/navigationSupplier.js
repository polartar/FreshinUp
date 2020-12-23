const ITEMS_SUPPLIER = [
  {
    action: 'icon-dashboard',
    title: 'Dashboard',
    to: '/supplier/dashboard'
  },
  {
    action: 'icon-companies',
    title: 'My company',
    to: '/supplier/company'
  },
  {
    action: 'icon-trucks',
    title: 'My Fleet',
    to: '/supplier/store'
  },
  {
    action: 'icon-events',
    title: 'Events',
    to: '/supplier/events'
  },
  {
    action: 'icon-documents',
    title: 'Documents',
    to: '/supplier/documents'
  }
]

export default (initialState = {}) => {
  const state = {
    ...initialState,
    items: ITEMS_SUPPLIER
  }

  const store = {
    state
  }

  return {
    namespaced: true,
    ...store
  }
}
