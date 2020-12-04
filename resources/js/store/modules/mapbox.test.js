import module from './mapbox'
import MockAdapter from 'axios-mock-adapter'
import axios from 'axios'
import { FIXTURE_MAPBOX_SEARCH_RESULT } from '../../../../tests/Javascript/__data__/mapbox'

describe('store/modules/mapbox', () => {
  test('the state has places', () => {
    const places = []
    const result = module({ places })
    expect(result).toHaveProperty('state')
    expect(result.state).toHaveProperty('places', places)
  })

  test('is a namespaced module', () => {
    const places = []
    const result = module({ places })
    expect(result).toHaveProperty('namespaced', true)
  })

  describe('Mutations', () => {
    test('SET_PLACES_LOADING', () => {
      const result = module({})
      expect(result.state.placesLoading).toBe(false)

      result.mutations.SET_PLACES_LOADING(result.state, true)
      expect(result.state.placesLoading).toBe(true)
    })
    test('SET_PLACES', () => {
      const result = module({})
      expect(result.state.places).toHaveLength(0)

      const places = FIXTURE_MAPBOX_SEARCH_RESULT.features
      result.mutations.SET_PLACES(result.state, places)
      expect(result.state.places).toMatchObject(places)
    })
  })

  describe('Getters', () => {
    test('placesLoading', () => {
      const result = module({})
      expect(result.getters.placesLoading({ placesLoading: true })).toBe(true)
      expect(result.getters.placesLoading({ placesLoading: false })).toBe(false)
    })
    test('places', () => {
      const result = module({})
      let places = []
      expect(result.getters.places({ places })).toHaveLength(0)

      places = FIXTURE_MAPBOX_SEARCH_RESULT.features
      expect(result.getters.places({ places })).toMatchObject(places)
    })
  })

  describe('Actions', () => {
    describe('getPlaces(payload)', () => {
      test('when payload.text is missing', async () => {
        const store = module({})
        const commit = jest.fn()
        const places = []
        try {
          await store.actions.getPlaces({ commit }, {})
        } catch (error) {
          expect(error.message).toEqual('Text is required')
        }
        expect(commit).not.toHaveBeenCalledWith('SET_PLACES', places)
      })
      test('when payload.accessToken is missing', async () => {
        const store = module({})
        const commit = jest.fn()
        const places = []
        try {
          store.actions.getPlaces({ commit }, {})
        } catch (error) {
          expect(error.message).toEqual('Access token is required')
        }
        expect(commit).not.toHaveBeenCalledWith('SET_PLACES', places)
      })
      test('otherwise', async () => {
        const places = FIXTURE_MAPBOX_SEARCH_RESULT.features
        const mock = new MockAdapter(axios)
        mock.onGet(/https:\/\/api\.mapbox\.com\/geocoding\/v5\/mapbox\.places.*/)
          .reply(200, { data: places })
        const store = module({})
        const commit = jest.fn()
        await store.actions.getPlaces({ commit }, {
          text: 'abc',
          accessToken: 'a1b2c3'
        })
        expect(commit).toHaveBeenCalledTimes(2)
        // expect(commit).toHaveBeenCalledWith('SET_PLACES_LOADING', true)
        // expect(commit).toHaveBeenCalledWith('SET_PLACES', places)
        // expect(commit).toHaveBeenCalledWith('SET_PLACES_LOADING', false)
      })
    })
  })
})
