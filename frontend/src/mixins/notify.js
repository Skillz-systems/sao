import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  methods: {
    toast(title, icon, text, variant) {
      this.$toast({
        component: ToastificationContent,
        props: {
          title,
          icon,
          text,
          variant,
        },
      })
    },
  },
}
