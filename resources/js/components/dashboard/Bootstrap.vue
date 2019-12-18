<template>
  <v-card>
    <v-card-title>
      <h3
        class="grey--text"
      >
        {{ title }}
      </h3>
    </v-card-title>
    <v-divider />
    <v-card-text>
      <v-layout
        align-center
        justify-space-around
        wrap
      >
        <v-avatar
          color="primary"
          :size="cycleSize"
        >
          <v-icon
            :size="iconSize"
            color="white"
          >
            {{iconName}}
          </v-icon>
        </v-avatar>
        <span
          class="text-xs-center mt-3 grey--text"
        >
          {{description}}
        </span>
      </v-layout>
    </v-card-text>
    <v-card-actions>
      <v-layout
        align-center
        justify-space-around
        wrap
        mb-2
      >
        <v-btn
          round
          color="primary"
          :to="{name: buttonTo}"
        >
          {{buttonText}}
        </v-btn>
      </v-layout>
    </v-card-actions>
  </v-card>
</template>

<script>
import '../../../fonts/css/fontello.css'

export default {
  props: {
    disabled: {
      type: Boolean,
      default: false
    },
    content: {
      type: Object,
      default: {
        title: '',
        description: '',
        button: ''
      }
    },
    icon: {
      type: Object,
      default: {
        size: 0,
        name: ''
      }
    },
    navTo: {
      type: String,
      default: ''
    }
  },
  data: function () {
    return {
      title: this.content.title,
      description: this.content.description,
      buttonText: this.content.button,
      buttonTo: this.navTo,
      iconName: this.icon.name,
      iconSize: (this.icon.size * 0.7) + 'px',
      cycleSize: this.icon.size + 'px'
    }
  }
}
</script>

<style>
  .v-btn__content {
    font-size: 12px;
    text-transform: none;
  }
</style>