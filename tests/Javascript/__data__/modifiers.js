export const FIXTURE_MODIFIER_AUTOCOMPLETE = {
  name: 'event_uuid',
  resource_name: 'events',
  label: 'Event',
  placeholder: 'All events',
  type: 'autocomplete',
  filter: 'name'
}

export const FIXTURE_MODIFIER_SELECT = {
  name: 'payment_uuid',
  resource_name: 'payment_types',
  label: 'Payment type',
  placeholder: 'All payment types',
  type: 'select'
}

export const FIXTURE_MODIFIER_DATE = {
  name: 'date_after',
  resource_name: 'date_after',
  label: 'Min Date',
  placeholder: 'Any',
  type: 'date'
}

export const FIXTURE_MODIFIER_TEXT = {
  name: 'min_price',
  resource_name: null,
  label: 'Min price',
  placeholder: 'Min price',
  type: 'text'
}
