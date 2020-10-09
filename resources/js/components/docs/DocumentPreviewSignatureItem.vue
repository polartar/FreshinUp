<template>
  <div>
    <div
      class="py-3"
    />
    <v-divider />
    <div
      class="body-1 mt-2"
    >
      {{ item }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: String,
      required: true
    }
  }
}
</script>
