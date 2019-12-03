import { storiesOf } from '@storybook/vue'
import { action } from '@storybook/addon-actions'
import StoreSummary from './StoreSummary'

storiesOf('FoodFleet|events/StoreSummary', module)
  .add(
    'data set',
    () => ({
      components: { StoreSummary },
      methods: {
        viewMemberProfile () {
          action('viewMemberProfile')()
        },
        removeMemberProfile () {
          action('removeMemberProfile')()
        }
      },
      data () {
        return {
          store: {
            owner: 'Dan Smith',
            lisence_due: 'Dec, 30 2020',
            phone: '938 374822',
            email: 'dan.simth@gmail.com',
            tags: ['SEAFOOD', 'SMOKED', 'DESSERT', 'BAY AREA', 'VEGAN OPTIONS', 'SMOKED', 'DESSERT', 'SEAFOOD', 'SMOKED']
          }
        }
      },
      template: `        
        <store-summary
          :store="store"
          @viewMemberProfile="viewMemberProfile"
          @removeMemberProfile="removeMemberProfile"
        />
      `
    })
  )
