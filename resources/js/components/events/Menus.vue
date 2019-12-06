<template>
  <v-layout>
    <v-card width="100%">
      <div class="pa-4">
        <v-layout row>
          <v-flex
            sm6
          >
            <v-layout
              row
              mb-6
              class="menu-title"
            >
              <span class="grey--text font-weight-bold title text-uppercase">{{ menuTitle }}</span>
            </v-layout>
          </v-flex>
          <v-flex
            sm6
          >
            <v-layout
              row
              mb-6
              class="add-new-item"
            >
              <v-btn
                color="primary"
                @click="create"
              >
                <v-icon>
                  add_circle
                </v-icon>
                <span>
                  Add New Item
                </span>
              </v-btn>
            </v-layout>
          </v-flex>
        </v-layout>
        <hr class="menu-title-line">
        <v-layout row>
          <v-flex>
            <menu-list
              :menus="menus"
              @manage-edit="edit"
              @manage-delete="del"
              @manage-multiple-delete="multipleDelete"
            />
          </v-flex>
        </v-layout>
        <v-layout row>
          <v-flex>
            <menu-modal
              v-model="dialog"
              :menu="menu"
              @save="save"
            />
          </v-flex>
        </v-layout>
      </div>
    </v-card>
  </v-layout>
</template>

<script>
import MenuList from './MenuList.vue'
import MenuModal from './MenuModal.vue'

export default {
  components: {
    MenuList,
    MenuModal
  },
  props: {
    menuTitle: {
      type: String,
      default: () => 'Fleet Member Name'
    },
    menus: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      dialog: false,
      menu: {
        uuid: null,
        item: null,
        servings: null,
        cost: null,
        description: null
      }
    }
  },
  methods: {
    edit (params) {
      this.dialog = true
      this.menu.uuid = params.uuid
      this.menu.item = params.item
      this.menu.servings = params.servings
      this.menu.cost = params.cost
      this.menu.description = params.description
      this.$emit('manage-edit', params)
    },
    del (params) {
      this.$emit('manage-delete', params)
    },
    multipleDelete (params) {
      this.$emit('manage-multiple-delete', params)
    },
    create () {
      this.dialog = true
      this.$emit('create')
    },
    save (params) {
      this.dialog = false
      this.$emit('save', params)
    }
  }
}
</script>

<style scoped>
.menu-title {
  padding: 4% 0 0 5%;
}

.add-new-item {
  justify-content: flex-end;
}

.menu-title-line {
  margin: 10px 0 12px 0;
}
</style>
