import App from '~/app'

describe('App', () => {
  test('has getBUSVersion', () => {
    expect(App.getBUSVersion).toBeInstanceOf(Function)
    expect(App.getBUSVersion()).toMatch(new RegExp('^\\d.\\d.\\d$'))
  })
})
