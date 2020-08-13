<template>
  <div>
    <div class="px-4 pt-4">
      <label class="d-block">
        <input
          type="text"
          class="py-2 px-3"
          style="width: 100%; border: 1px solid #dee2e6; border-radius: 5px;"
          placeholder="Search by Fleet Member name"
        >
      </label>
      <div class="my-2 py-2">
        <div>
          <button
            style="font-weight: bold; color: lightslategray; outline: 0;"
            @click="toggleShowFilter"
          >
            {{ showFilters ? 'Hide' : 'Show' }} Filters
          </button>
        </div>
        <div
          v-show="showFilters"
        >
          <div
            class="d-flex align-end justify-space-between pt-2"
          >
            <div class="pr-2">
              <label
                class="d-block text-uppercase font-weight-bold py-2 grey--text text--darken-1"
                for="state"
              >
                state of incorporation
              </label>
              <select
                id="state"
                name="state"
                class="py-2 px-3"
                style="width: 100%; border: 1px solid #dee2e6; border-radius: 5px;"
              >
                <option
                  value=""
                  selected
                  hidden
                >
                  Select state
                </option>
                <option value="illinois">
                  Illinois
                </option>
              </select>
            </div>
            <div class="pr-2">
              <label
                class="d-block text-uppercase font-weight-bold py-2 grey--text text--darken-1"
                for="type"
              >
                type
              </label>
              <select
                id="type"
                name="type"
                class="py-2 px-3"
                style="width: 100%; border: 1px solid #dee2e6; border-radius: 5px;"
              >
                <option
                  value=""
                  selected
                  hidden
                >
                  All types
                </option>
                <option value="mobile">
                  Mobile
                </option>
              </select>
            </div>
            <div class="pr-2">
              <label
                class="d-block text-uppercase font-weight-bold py-2 grey--text text--darken-1"
                for="tags"
              >
                tags
              </label>
              <select
                id="tags"
                name="tags"
                class="py-2 px-3"
                style="width: 100%; border: 1px solid #dee2e6; border-radius: 5px;"
              >
                <option
                  value=""
                  selected
                  hidden
                >
                  Select tags
                </option>
                <option value="tag1">
                  Tag 1
                </option>
                <option value="tag2">
                  Tag 2
                </option>
              </select>
            </div>
            <div>
              <button
                class="px-3 py-2"
                style="background-color: lightgrey; border-radius: 5px; color: white; width: 100%;"
              >
                Clear all filters
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="px-4"
      style="border-top: 1px solid gainsboro;"
    >
      <table>
        <thead>
          <tr>
            <th class="font-weight-bold grey--text text--darken-1" style="padding: unset;">
              <input type="checkbox">
            </th>
            <th class="font-weight-bold grey--text text--darken-1">
              Fleet member
            </th>
            <th class="font-weight-bold grey--text text--darken-1">
              State of incorporation
            </th>
            <th class="font-weight-bold grey--text text--darken-1">
              Tags
            </th>
            <th class="font-weight-bold grey--text text--darken-1">
              Manage
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(member, index) in members"
            :key="index"
          >
            <td style="padding: unset;">
              <input type="checkbox">
            </td>
            <td>
              <div class="teal--text teal--darken-4" style="font-size: 20px;">{{ member.name }}</div>
              <div class="grey--text text--darken-2">{{ member.type }}</div>
            </td>
            <td>
              {{ member.state }}
            </td>
            <td>
              {{ member.tags }}
            </td>
            <td>
              <button
                class="py-1 px-3"
                style="color: white; background: seagreen; border-radius: 5px;"
              >
                {{ member.manage }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div
        class="py-4"
        style="text-align: center;"
      >
        <v-pagination
          v-model="page"
          :length="6"
        />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data () {
    return {
      page: 1,
      showFilters: false,
      selected: [],
      headers: [
        { text: 'Fleet member', value: 'name' },
        { text: 'State of incorporation', value: 'state' },
        { text: 'Tags', value: 'tags' },
        { text: 'Manage', value: 'manage' }
      ],
      members: [
        { name: 'Test 1', state: 'Test state 1', tags: ['Tag 1', 'Tag 2'], manage: 'Assign', type: 'Mobile' },
        { name: 'Test 2', state: 'Test state 2', tags: ['Tag 1', 'Tag 2'], manage: 'Assign', type: 'Mobile' },
        { name: 'Test 3', state: 'Test state 3', tags: ['Tag 1', 'Tag 2'], manage: 'Assign', type: 'Mobile' },
        { name: 'Test 4', state: 'Test state 4', tags: ['Tag 1', 'Tag 2'], manage: 'Assign', type: 'Mobile' }
      ]
    }
  },
  methods: {
    toggleShowFilter () {
      this.showFilters = !this.showFilters
    }
  }
}
</script>
<style scoped lang="scss">
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;

  & th, & td {
    border-bottom: 1px solid gainsboro;
    padding: 15px;
    text-align: left;
  }

  & th {
    text-transform: uppercase;
  }
}
</style>
