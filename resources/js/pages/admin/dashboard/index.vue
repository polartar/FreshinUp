<script>
  import { mapGetters } from 'vuex'
  import { USER_TYPE } from '../../../store/modules/userTypes'
  import BusDashboard from 'fresh-bus/pages/admin/index.vue'
  import get from 'lodash/get'

  export default {
    extends: BusDashboard,
    layout: 'admin',
    computed: {
      ...mapGetters(['currentUser'])
    },
    watch: {
      '$store.getters.currentUser' (authUser) {
        if (get(authUser, 'type') == USER_TYPE.SUPPLIER) {
          this.$router.push({ path: '/admin/supplier/dashboard' })
        }
      }
    },
    beforeRouteEnterOrUpdate (vm, from, to, next) {
      vm.$store.dispatch('page/setLoading', false)
      next && next()
    }
  }
</script>
