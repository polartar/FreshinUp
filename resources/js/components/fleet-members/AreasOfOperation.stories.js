import AreasOfOperation from './AreasOfOperation'
import { storiesOf } from '@storybook/vue'
import { FIXTURE_STORE_AREAS } from '../../../../tests/Javascript/__data__/storeAreas'
import { action } from '@storybook/addon-actions'
import AreaForm from './AreaForm'

export const Default = () => ({
  components: { AreasOfOperation },
  template: `
    <v-container>
      <AreasOfOperation/>
    </v-container>
  `
})

export const IsLoading = () => ({
  components: { AreasOfOperation },
  template: `
    <v-container>
      <AreasOfOperation is-loading/>
    </v-container>
  `
})

export const Populated = () => ({
  components: { AreasOfOperation },
  data () {
    return {
      areas: FIXTURE_STORE_AREAS
    }
  },
  methods: {
    onManage (act, item) {
      action('manage')(act, item)
    },
    onManageMultiple (act, items) {
      action('manage-multiple')(act, items)
    }
  },
  template: `
    <v-container>
      <AreasOfOperation
        :items="areas"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      />
    </v-container>
  `
})

export const WithAddForm = () => ({
  components: { AreasOfOperation, AreaForm },
  data () {
    return {
      areas: FIXTURE_STORE_AREAS,
      newOperation: true,
      formIsLoading: false
    }
  },
  methods: {
    onManage (act, item) {
      action('manage')(act, item)
    },
    onManageMultiple (act, items) {
      action('manage-multiple')(act, items)
    },
    onManageAdd (payload) {
      action('onManageAdd')(payload)
    }
  },
  template: `
    <v-container>
      <AreasOfOperation
        :items="areas"
        @manage="onManage"
        @manage-multiple="onManageMultiple"
      >
        <template v-slot:head>
          <v-flex shrink>
            <v-dialog
              v-model="newOperation"
              max-width="600"
            >
              <template v-slot:activator="{ on }">
                <v-btn
                  slot="activator"
                  color="primary"
                  text
                  @click="newOperation = true"
                >
                  <v-icon
                    dark
                    left
                  >
                    add_circle_outline
                  </v-icon>
                  Add new area
                </v-btn>
              </template>
              <v-card>
                <div class="d-flex justify-space-between align-center">
                  <v-card-text class="grey--text subheading font-weight-bold">
                    Add new area
                  </v-card-text>
                  <v-btn
                    small
                    round
                    depressed
                    color="grey"
                    class="white--text"
                    @click="newOperation = false"
                  >
                    <v-flex>
                      <v-icon
                        small
                        class="white--text"
                      >
                        fa fa-times
                      </v-icon>
                    </v-flex>
                    <v-flex>
                      Close
                    </v-flex>
                  </v-btn>
                </div>
                <v-divider/>
                <area-form
                  :is-loading="formIsLoading"
                  class="ma-2"
                  @cancel="newOperation = false"
                  @input="onManageAdd"
                />
              </v-card>
            </v-dialog>
          </v-flex>
        </template>
      </AreasOfOperation>
    </v-container>
  `
})

storiesOf('FoodFleet|components/fleet-members/AreasOfOperation', module)
  .addParameters({
    backgrounds: [
      { name: 'default', value: '#f1f3f6', default: true }
    ]
  })
  .add('Default', Default)
  .add('IsLoading', IsLoading)
  .add('Populated', Populated)
  .add('WithAddForm', WithAddForm)
