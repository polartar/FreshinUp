import Vue from 'vue'
import Component from '~/components/charts/Lines.vue'

describe('charts/Lines', () => {
  // Component instance "under test"
  describe('Snapshots', () => {
    let el
    beforeEach(() => {
      el = document.createElement('div')
    })
    it('should render a canvas', () => {
      const vm = new Vue({
        components: { Component },
        render: function (createElement) {
          return createElement(
            Component
          )
        }
      }).$mount(el)
      expect(vm.$el).toMatchSnapshot()
    })
  })
})
