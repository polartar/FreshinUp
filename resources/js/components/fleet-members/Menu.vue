<template>
  <v-card>
    <v-card-title class="px-3">
      <v-layout
        align-center
        justify-center
        row
        fill-height
      >
        <v-flex>
          <h3 class="grey--text">
            Fleet Member Menu
          </h3>
        </v-flex>
        <v-flex shrink>
          <v-dialog
            v-model="newMenuItem"
            max-width="400"
          >
            <template v-slot:activator="{ on }">
              <v-btn
                slot="activator"
                color="primary"
                text
                @click="newMenuItem = true"
              >
                <v-icon
                  dark
                  left
                >
                  add_circle_outline
                </v-icon>Add new menu item
              </v-btn>
            </template>
            <v-card>
              <v-divider />
              <v-card-text class="grey--text">
                Coming Soon
              </v-card-text>
              <v-divider />
            </v-card>
          </v-dialog>
        </v-flex>
      </v-layout>
    </v-card-title>
    <hr>

    <v-card-text class="primary--text">
      <div class="ff-fleet-members__menu-item-text">
        Nothing here yet.
        <v-dialog
          v-model="newItem"
          max-width="400"
        >
          <template v-slot:activator="{ on }">
            <a
              href="#"
              @click.prevent="newItem=true"
            >
              Add new item
            </a>
          </template>
          <v-card>
            <v-divider />
            <v-card-text class="grey--text">
              Coming Soon
            </v-card-text>
            <v-divider />
          </v-card>
        </v-dialog>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'Menu',
  props: {
    menus: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      newMenuItem: false,
      newItem: false
    }
  }
}
</script>

<style lang="scss" scoped>
  .ff-fleet-members__menu-item-text {
    text-align: center;
  }
</style>
