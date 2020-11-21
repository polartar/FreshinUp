<template>
  <MglMap
    :access-token="accessToken"
    :map-style="mStyle"
    v-bind="$attrs"
  >
    <slot />
  </MglMap>
</template>
<script>
import { MglMap } from 'vue-mapbox'
import { lazyLoad } from '../../utils'

/**
 * Map wrapper of MapBox
 * TODO: use API directly instead of lib from NPM
 * const map = new mapboxgl.Map({
 *   container: options.container,
 *   style: options.style,
 *   center: options.center,
 *   zoom: 15
 * })
 *
 * // add navigation
 * const nav = new mapboxgl.NavigationControl()
 * map.addControl(nav, 'top-left')
 */
export default {
  components: {
    MglMap
  },
  props: {
    // center,
    // max-bounds
    accessToken: { type: String, required: true },
    mStyle: { type: String, default: 'mapbox://styles/mapbox/streets-v11' }
  },
  created () {
    // TODO need tests
    const id = 'f-map-' + Math.round(Math.random() * 10 ** 10).toString(16)
    const container = document.body
    lazyLoad({
      container,
      type: 'link',
      id,
      href: 'https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css',
      rel: 'stylesheet'
    })
    this.$once('hook:destroyed', () => {
      const element = container.querySelector(`#${id}`)
      if (element) {
        element.remove()
      }
    })
  }
}
</script>
