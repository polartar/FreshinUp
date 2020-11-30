<template>
  <v-container
    fill-height
    fluid
    grid-list-lg
  >
    <v-layout justify-center>
      <v-flex
        :class="flexClass"
      >
        <slot>
          <v-stepper
            v-if="steps.length"
            :value="currentStep"
          >
            <v-stepper-header>
              <template v-for="(step, idx) in steps">
                <v-stepper-step
                  :key="step.key"
                  :complete="stepValue > idx + 1"
                  :step="idx + 1"
                >
                  {{ step.label }}
                </v-stepper-step>
                <v-divider
                  v-if="idx < steps.length - 1"
                  :key="'divider-'+idx"
                />
              </template>
            </v-stepper-header>

            <v-stepper-items>
              <template v-for="(step, idx) in steps">
                <slot
                  :name="'step-'+(idx+1)"
                  :num="idx + 1"
                  :step="step"
                />
              </template>
            </v-stepper-items>
          </v-stepper>
        </slot>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  props: {
    steps: {
      type: Array,
      default: () => []
    },
    currentStep: {
      type: Number,
      default: 1
    },
    flexClass: {
      type: String,
      default: 'lg7 md9'
    }
  },
  data () {
    return {
      stepValue: 1
    }
  }
}
</script>
