import { version, name } from '../../composer.json'
import page from './store/modules/page'
import devices from './store/modules/devices'
import financialModifiers from './store/modules/financialModifiers'
import financialReports from './store/modules/financialReports'
import payments from './store/modules/payments'
import paymentTypes from './store/modules/paymentTypes'
import paymentStatuses from './store/modules/paymentStatuses'
import financialsummary from './store/modules/financialsummary'
import squares from './store/modules/squares'
import documents from './store/modules/documents'
import documentStatuses from './store/modules/documentStatuses'
import documentTemplates from './store/modules/documentTemplates'
import documentTypes from './store/modules/documentTypes'
import transactions from './store/modules/transactions'
import companyDetails from './store/modules/companyDetails'
import eventSummary from './store/modules/eventSummary'
import eventTypes from './store/modules/eventTypes'
import events from './store/modules/events'
import eventHistories from './store/modules/eventHistories'
import venues from './store/modules/venues'
import venueStatuses from './store/modules/venueStatuses'
import stores from './store/modules/stores'
import storeStatuses from './store/modules/storeStatuses'
import storeTypes from './store/modules/storeTypes'
import storeAreas from './store/modules/storeAreas'
import companies from './store/modules/companies'
import locations from './store/modules/locations'
import companyOwners from './store/modules/companyOwners'
import eventStatuses from './store/modules/eventStatuses'
import eventMenuItems from './store/modules/eventMenuItems'
import locationCategories from './store/modules/locationCategories'
import menuItems from './store/modules/menuItems'
import messages from './store/modules/messages'
import mapbox from './store/modules/mapbox'

export default () => {
  return {
    name,
    version,
    pages: require.context('./pages', true, /\.vue$/),
    layouts: require.context('./layouts', false, /\.vue$/),
    store: {
      page,
      devices,
      financialModifiers,
      financialReports,
      financialsummary,
      payments,
      paymentTypes,
      paymentStatuses,
      squares,
      documents,
      documentStatuses,
      documentTemplates,
      documentTypes,
      transactions,
      companies,
      companyDetails,
      companyOwners,
      events,
      eventStatuses,
      eventMenuItems,
      eventSummary,
      eventTypes,
      eventHistories,
      venues,
      venueStatuses,
      stores,
      storeStatuses,
      storeTypes,
      storeAreas,
      locations,
      locationCategories,
      messages,
      mapbox,
      menuItems
    }
  }
}
