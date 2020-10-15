import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import PublishingForm from './PublishingForm.vue'
import { FIXTURE_DOCUMENT } from 'tests/__data__/documents'

// Components
storiesOf('FoodFleet|doc/PublishingForm', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { PublishingForm },
    methods: {
      changePublishing (params) {
        action('dataChange')(params)
      },
      onSaveClick (params) {
        action('data-save')(params)
      }
    },
    template: `
      <v-container>
        <PublishingForm
          @data-change="changePublishing"
          @data-save="onSaveClick"
        />
      </v-container>
    `
  }))
  .add('initdata is set', () => ({
    components: { PublishingForm },
    data () {
      return {
        isValid: true,
        doc: FIXTURE_DOCUMENT
      }
    },
    methods: {
      changePublishing (params) {
        action('dataChange')(params)
      },
      onSaveClick (params) {
        action('data-save')(params)
      }
    },
    template: `
      <v-container>
        <PublishingForm
          :isvalid="isValid"
          :initdata="doc"
          @data-change="changePublishing"
          @data-save="onSaveClick"
        />
      </v-container>
    `
  }))
