if (typeof window.URL.createObjectURL === 'undefined') {
  window.URL.createObjectURL = () => {
    // Do nothing
    // Mock this function for mapbox-gl to work
  }
}

jest.mock('mapbox-gl/dist/mapbox-gl', () => ({
  GeolocateControl: jest.fn(),
  Map: jest.fn(() => ({
    addControl: jest.fn(),
    on: jest.fn(),
    remove: jest.fn()
  })),
  NavigationControl: jest.fn()
}))
