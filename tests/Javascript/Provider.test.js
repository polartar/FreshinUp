import Provider from '../../resources/js/Provider'
import { version, name } from '../../composer.json'
import isFunction from 'lodash/isFunction'

describe('Provider', () => {
  test('has version from composer.json', () => {
    expect(Provider()).toHaveProperty('version', version)
  })
  test('has name from composer.json', () => {
    expect(Provider()).toHaveProperty('name', name)
  })
  test('has pages context (actual)', () => {
    expect(Provider()).toHaveProperty('pages')
    expect(isFunction(Provider().pages)).toEqual(true)
  })
  test('has store', () => {
    const result = Provider()
    expect(result).toHaveProperty('store')
  })
  test('has store module example', () => {
    const result = Provider()
    const expectedStoreModules = [
      'page',
      'devices',
      'financialModifiers',
      'financialReports',
      'paymentTypes',
      'financialsummary',
      'squares',
      'documents',
      'documentStatuses',
      'documentTypes',
      'transactions',
      'companyDetails',
      'eventSummary',
      'eventTypes',
      'eventHistories',
      'venues',
      'venueStatuses',
      'stores',
      'storeStatuses',
      'storeTypes',
      'storeAreas',
      'companies',
      'locations'
    ]
    expectedStoreModules.forEach((value) => {
      expect(result.store).toHaveProperty(value)
      expect(isFunction(result.store[value])).toEqual(true)
    })
  })
})
