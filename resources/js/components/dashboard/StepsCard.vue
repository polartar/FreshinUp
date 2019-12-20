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
          :color="iconColor"
          :size="cycleSize"
        >
          <v-icon
            :size="iconSize"
            color="white"
          >
            {{ iconName }}
          </v-icon>
        </v-avatar>
        <span
          class="text-xs-center mt-3 grey--text"
        >
          {{ description }}
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
          v-if="disabled"
          round
          disabled
        >
          {{ buttonText }}
        </v-btn>
        <v-btn
          v-else
          round
          color="primary"
          :href="buttonTo"
        >
          {{ buttonText }}
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
      default: () => {
        return {
          title: '',
          description: '',
          button: ''
        }
      }
    },
    icon: {
      type: Object,
      default: () => {
        return {
          size: 0,
          name: ''
        }
      }
    },
    navTo: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      title: this.content.title,
      description: this.content.description,
      buttonText: this.content.button,
      buttonTo: this.navTo,
      iconName: this.icon.name,
      iconSize: (this.icon.size * 0.7) + 'px',
      cycleSize: this.icon.size + 'px',
      disabledNav: this.disabled
    }
  },
  computed: {
    iconColor () {
      return this.disabled ? 'rgba(0, 0, 0, 0.12)' : 'primary'
    }
  }
}
</script>

<style scoped>
  .v-btn__content {
    font-size: 12px;
    text-transform: none;
  }
</style>
