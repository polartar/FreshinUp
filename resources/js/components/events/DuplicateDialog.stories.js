import { storiesOf } from '@storybook/vue'

// Components
import DuplicateDialog from './DuplicateDialog.vue'

storiesOf('FoodFleet|components/event/DuplicateDialog', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { DuplicateDialog },
    data () {
      return {
        duplicateDialog: true,
        duplicating: true
      }
    },
    template: `
      <v-container>
        <duplicate-dialog
          :duplicateDialog="duplicateDialog"
          :duplicating="duplicating"
        />
      </v-container>
    `
  }))
