import App from 'foodfleet/app'

describe('App', () => {
  test('appInstance is created', async () => {
    expect(App).toHaveProperty('_router')
  })
})
