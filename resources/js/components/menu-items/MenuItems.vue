<template>
  <v-card>
    <v-card-title class="grey--text">
      <v-layout
        row
        space-between
        align-center
      >
        <v-flex>
          <h3>Fleet Member Menu</h3>
        </v-flex>
        <v-flex shrink>
          <v-btn
            v-show="!newDialog"
            slot="activator"
            color="primary"
            text
            @click="newDialog = true"
          >
            <v-icon
              dark
              left
            >
              add_circle_outline
            </v-icon>Add new menu item
          </v-btn>
        </v-flex>
      </v-layout>
    </v-card-title>
    <v-divider />
    <v-card-text class="primary--text">
      <menu-item-list
        v-if="!newDialog"
        v-bind="$attrs"
        v-on="$listeners"
      />
      <slot
        v-else
        name="new-form"
        :close="() => newDialog = false"
      />
    </v-card-text>
    <v-divider />
  </v-card>
</template>

<script>
import MenuItemList from './MenuItemList'
export default {
  components: {
    MenuItemList
  },
  data () {
    return {
      newDialog: false
    }
  }

}
</script>
