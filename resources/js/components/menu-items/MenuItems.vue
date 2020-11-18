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
            v-show="dialog === ''"
            slot="activator"
            color="primary"
            text
            @click="onChangeDialog('new')"
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
        v-if="dialog === ''"
        v-bind="$attrs"
        v-on="$listeners"
      />
      <slot
        v-else-if="dialog === 'new'"
        name="new-form"
        :close="() => onChangeDialog('')"
      />
      <slot
        v-else-if="dialog === 'edit'"
        name="edit-form"
        :close="() => onChangeDialog('')"
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
  props: {
    dialog: { type: String, default: '' }
  },
  methods: {
    onChangeDialog (value) {
      this.$emit('change-dialog', value)
    }
  }
}
</script>
