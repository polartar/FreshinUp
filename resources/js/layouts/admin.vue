<script>
import { mapState } from 'vuex'
import defaultLayout from './default.vue'
export default {
  extends: defaultLayout,
  data: () => ({
    isAdmin: true,
    navDrawerLogo: true,
    navDrawerNoActions: true
  }),
  computed: {
    ...mapState('navigationAdmin', [
      'items',
      'logo'
    ])
  }
}
</script>
<style>
    .f-page__title--admin {
        color: white;
    }
</style>
