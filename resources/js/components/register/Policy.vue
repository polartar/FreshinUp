<template>
  <v-flex column>
    <v-flex>
      <h1 class="text-xs-center primary--text">
        Terms and Conditions
      </h1>
    </v-flex>
    <v-flex
      md6
      hidden-sm-and-down
    >
      <v-spacer />
    </v-flex>
    <v-flex>
      <div
        class="notes"
        @scroll="handleScroll"
      >
        {{ notes }}
      </div>
    </v-flex>
  </v-flex>
</template>

<script>
export default {
  data () {
    return {
      notes: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin dapibus id sem in iaculis. Donec ornare, nisl ac egestas lacinia, risus ante dapibus urna, quis suscipit dui nibh et magna. Sed vehicula justo finibus, tincidunt erat et, rutrum nisi. Donec ultrices, quam et viverra interdum, dolor metus cursus metus, at tincidunt augue tellus ut tellus. Donec tincidunt malesuada libero, et faucibus tortor sodales non. Sed ac mi ullamcorper, tempus diam non, bibendum arcu. Cras ut tempor ligula. Fusce facilisis magna mi, vel tincidunt arcu viverra ut. Aenean ac finibus mi. Donec imperdiet diam a dictum semper. Phasellus pellentesque, arcu quis elementum maximus, diam arcu fringilla ex, ac egestas nisi risus eget nisi. Etiam tincidunt nunc nibh, sed varius mauris aliquet a. Praesent sed fermentum arcu. In at neque dictum, ullamcorper augue vel, blandit erat. Nunc lectus purus, interdum et volutpat vel, porta at sem. Mauris eu semper nisi. Cras quis nibh fringilla, pulvinar sem at, rutrum nibh. Quisque viverra velit turpis. Sed vulputate eros nec turpis tincidunt consequat. In hac habitasse platea dictumst. Sed aliquet blandit ligula, vel accumsan lacus tempor pulvinar. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam malesuada velit eget sollicitudin faucibus. Cras at urna vitae nunc luctus dapibus. Nunc aliquet ante a diam sagittis, et luctus nisi vestibulum. Vivamus ultricies sapien est, et placerat eros malesuada sed. Maecenas ornare egestas elementum.',
      scroll: false
    }
  },
  methods: {
    handleScroll: function (e) {
      if ((e.srcElement.offsetHeight + e.srcElement.scrollTop) >= e.srcElement.scrollHeight) {
        this.scroll = true
      } else {
        this.scroll = false
      }
      this.$emit('set-enabled-policy-next', this.scroll)
    }
  }

}
</script>

<style scoped>
.notes {
  overflow: auto;
  height: 150px;
  border: 1px solid lightgrey;
  padding: 24px 0 26px 20px;
}
</style>
