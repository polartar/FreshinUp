<template>
  <v-menu
    v-model="showMenu"
    offset-y
  >
    <template v-slot:activator="on">
      <v-text-field
        :value="showText"
        append-icon="fas fa-caret-down"
        :placeholder="placeholder"
        readonly
        hide-details
        v-bind="$attrs"
        @click="toggleMenu(on)"
      />
    </template>
    <v-autocomplete
      ref="search"
      v-model="model"
      append-icon=""
      :items="items"
      :loading="isLoading"
      placeholder="search"
      :clearable="!notClearable"
      :hide-no-data="!isNoDataAllowed"
      hide-details
      :item-text="resultsTextKey"
      :item-value="resultsIdKey"
      label=""
      return-object
      v-bind="$attrs"
      multiple
      autofocus
      :menu-props="{ value: showAutocomplete, closeOnContentClick: false }"
      v-on="$listeners"
      @input.native="termUpdate"
    >
      <template v-slot:prepend-item>
        <v-list-tile v-show="model && model.length > 0">
          <span class="search-section-title">SELECTION</span>
        </v-list-tile>
        <v-list-tile
          v-for="selectedItem in model"
          :key="selectedItem[resultsIdKey]"
          ripple
          @click="cancelSelected(selectedItem[resultsIdKey])"
        >
          <v-list-tile-action>
            <v-icon color="primary">
              fa-check-square
            </v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ selectedItem.name }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile v-show="items.length > 0">
          <span class="search-section-title">RESULTS</span>
        </v-list-tile>
      </template>
      <template v-slot:selection="{ item, index }">
        <span />
      </template>
      <template v-slot:no-data>
        <span />
      </template>
    </v-autocomplete>
  </v-menu>
</template>
<script>
import axios from 'axios'
import Simple from 'fresh-bus/components/search/simple'

export default {
  extends: Simple,
  props: {
    value: {
      type: Array,
      default: null
    }
  },
  data () {
    return {
      showMenu: false,
      showAutocomplete: false
    }
  },
  computed: {
    showText () {
      if (this.model instanceof Array) {
        if (this.model.length === 0) {
          return null
        }
        return this.model.length > 1 ? `${this.model.length} selected` : this.model[0].name
      }

      return null
    }
  },
  watch: {
    showMenu (value) {
      if (value) {
        setTimeout(() => {
          const input = this.$refs.search.$refs.input
          input.focus()
          this.showAutocomplete = true
        }, 100)
      } else {
        this.results = null
        this.showAutocomplete = false
      }
    }
  },
  beforeMount () {
    if (!this.value || this.value.length === 0) {
      return
    }

    let params = {}
    params['filter[' + this.resultsIdKey + ']'] = this.value
    this.isLoading = true
    axios.get(this.url, {
      params
    })
      .then(response => {
        this.results = response.data.data
        if (this.results.length > 0) {
          this.model = this.results
        }
      })
      .catch(err => {
        console.error(err)
      })
      .finally(() => (this.isLoading = false))
  },
  methods: {
    toggleMenu () {
      this.showMenu = !this.showMenu
    },
    cancelSelected (value) {
      this.model = this.model.filter(item => item[this.resultsIdKey] !== value)
    }
  }
}
</script>
<style lang="styl" scoped>
  .search-section-title{
    font-size: 13px;
    font-weight: bold;
  }
</style>
