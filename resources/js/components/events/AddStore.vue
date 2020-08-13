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
            @click="toggleShowFilter"
          >
            Show Filters
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
                class="d-block text-uppercase"
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
                class="d-block text-uppercase"
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
                class="d-block text-uppercase"
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
      <table style="width: 100%;">
        <thead>
          <tr>
            <th style="border-bottom: 1px solid gainsboro;">
              <input type="checkbox">
            </th>
            <th style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro; text-transform: uppercase; font-size: 12px; font-weight: 600; color: gray;">
              Fleet member
            </th>
            <th style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro; text-transform: uppercase; font-size: 12px; font-weight: 600; color: gray;">
              State of incorporation
            </th>
            <th style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro; text-transform: uppercase; font-size: 12px; font-weight: 600; color: gray;">
              Tags
            </th>
            <th style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro; text-transform: uppercase; font-size: 12px; font-weight: 600; color: gray;">
              Manage
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(member, index) in members"
            :key="index"
          >
            <td style="border-bottom: 1px solid gainsboro;">
              <input type="checkbox">
            </td>
            <td style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro;">
              {{ member.name }}
            </td>
            <td style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro;">
              {{ member.state }}
            </td>
            <td style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro;">
              {{ member.tags }}
            </td>
            <td style="padding: 15px; text-align: left; border-bottom: 1px solid gainsboro;">
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
        { name: 'Test 1', state: 'Test state 1', tags: ['Tag 1', 'Tag 2'], manage: 'Assign' },
        { name: 'Test 2', state: 'Test state 2', tags: ['Tag 1', 'Tag 2'], manage: 'Assign' },
        { name: 'Test 3', state: 'Test state 3', tags: ['Tag 1', 'Tag 2'], manage: 'Assign' },
        { name: 'Test 4', state: 'Test state 4', tags: ['Tag 1', 'Tag 2'], manage: 'Assign' }
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
