<template>
  <div>
    <v-layout
      row
      align-center
      pt-3
    >
      <v-btn
        flat
        small
      >
        <div class="back-btn-inner">
          <v-icon>fas fa-arrow-left</v-icon>
          <span>Return to Events Details</span>
        </div>
      </v-btn>
    </v-layout>
    <v-layout
      row
      justify-space-between
      ma-2
    >
      <h2 class="white--text">
        Fleet Member Name
      </h2>
      <v-flex
        text-xs-right
        sm2
        xs12
      >
        <status-select
          :value="store.status"
          :options="statuses"
        />
      </v-flex>
    </v-layout>
    <v-divider />
    <br>
    <v-layout
      row
      pa-2
    >
      <v-flex
        md8
        sm8
      >
        <v-card class="mx-2">
          <v-tabs
            v-model="tab"
            background-color="transparent"
            color="basil"
            grow
          >
            <v-tab
              v-for="item in items"
              :key="item"
            >
              {{ item }}
            </v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab">
            <v-tab-item>
              <v-card flat color="basil">
                <menus />
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat color="basil">
                <document-section
                  :statuses="statuses"
                />
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat color="basil">
                <messages />
              </v-card>
            </v-tab-item>
          </v-tabs-items>
        </v-card>
      </v-flex>
      <v-flex
        md4
        sm4
      >
        <v-card class="mx-2">
          <v-divider />
          <store-summary
            :store="store"
          />
        </v-card>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import StatusSelect from '~/components/events/StatusSelect'
import Menus from '~/components/events/Menus.vue'
import DocumentSection from '~/components/events/DocumentSection.vue'
import Messages from '~/components/events/Messages.vue'
import StoreSummary from '~/components/events/StoreSummary.vue'

export default {
  components: {
    StatusSelect,
    Menus,
    DocumentSection,
    Messages,
    StoreSummary
  },
  data () {
    return {
      tab: null,
      items: [
        'Event Menu', 'Event Documents', 'Event Activity',
      ],
      statuses: [
        { id: 1, name: 'Draft' },
        { id: 2, name: 'Pending' },
        { id: 3, name: 'Confirmed' },
        { id: 4, name: 'Declined' }
      ],
      store: {
        status: 1,
        owner: 'Dan Smith',
        lisence_due: 'Dec, 30 2020',
        phone: '938 374822',
        email: 'dan.simth@gmail.com',
        tags: ['SEAFOOD', 'SMOKED', 'DESSERT', 'BAY AREA', 'VEGAN OPTIONS', 'SMOKED', 'DESSERT', 'SEAFOOD', 'SMOKED']
      }
    }
  }
}
</script>

<style scoped>
</style>
