import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'

// Components
import SaveSearch from './SaveSearch.vue'

const modifiers = [
  { id: 1, label: 'Location' },
  { id: 2, label: 'Company' }
]

storiesOf('FoodFleet|reportables/SaveSearch', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('default', () => ({
    components: { SaveSearch },
    methods: {
      onClose () {
        action('Close')('close')
      },
      onSave (params) {
        action('Save')(params)
      }
    },
    data () {
      return {
        modifiers: modifiers
      }
    },
    template: `
  <v-container>
       <save-search 
            :modifiers="modifiers"
            @close="onClose"
            @save="onSave"
            />
  </v-container>
`
  }))
  .add('inside dialog', () => ({
    components: { SaveSearch },
    data () {
      return {
        modifiers: modifiers,
        dialog: false
      }
    },
    methods: {
      closeDialog () {
        this.dialog = false
      },
      onSave (params) {
        this.dialog = false
        action('Save')(params)
      }
    },
    template: `
    <v-container>
        <v-dialog
            v-model="dialog"
            scrollable
            max-width="436"
          >
            <v-btn
              slot="activator"
              color="primary"
              dark
            >
              Save Search
            </v-btn>
             <save-search 
              :modifiers="modifiers"
              @close="closeDialog"
              @save="onSave"
              />
        </v-dialog>
    </v-container>
`
  }))
