<template>
  <div>
    <admin v-if="userType !== 'client'" />
    <client v-if="userType === 'client'" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Admin from '@/views/overview/Admin.vue'
import Client from '@/views/overview/Client.vue'

export default {
  components: {
    Admin,
    Client,
  },
  computed: {
    ...mapGetters({
      userType: 'auth/getUserType',
    }),
  },
}
</script>
