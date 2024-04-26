<template>
  <b-card class="px-lg-2">
    <validation-observer ref="updatePasswordValidation">
      <b-form @submit.prevent>
        <h4>
          Change your password
        </h4>
        <p>You can only change your password twice within 24 hours</p>
        <b-row class="mt-1">
          <b-col lg="6">
            <validation-provider
              #default="{ errors }"
              name="current password"
              rules="required"
            >
              <b-form-group>
                <label
                  for="name"
                  class="mb-lg-1 font-weight-bolder"
                >Current Password</label>
                <b-form-input
                  id="name"
                  v-model="currentPassword"
                  type="password"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col lg="6">
            <validation-provider
              #default="{ errors }"
              name="new password"
              rules="required"
            >
              <b-form-group>
                <label
                  for="email"
                  class="mb-lg-1 font-weight-bolder"
                >New Password</label>
                <b-form-input
                  id="email"
                  v-model="newPassword"
                  type="password"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col cols="12">
            <div class="mt-1">
              <b-button
                v-if="!isLoading"
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
                @click="updatePassword"
              >
                Save
              </b-button>
              <b-button
                v-if="isLoading"
                disabled
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
              >
                Saving...
              </b-button>
            </div>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import { required } from '@validations'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      isLoading: false,
      currentPassword: null,
      newPassword: null,
      required,
    }
  },
  methods: {
    updatePassword() {
      this.isLoading = true
      this.$refs.updatePasswordValidation.validate().then(success => {
        if (success) {
          const form = {
            currentPassword: this.currentPassword,
            newPassword: this.newPassword,
          }
          this.$store.dispatch('account/updatePassword', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.toast('Update Password', 'CheckCircleIcon', message, 'success')
          }).catch(err => {
            this.isLoading = false
            this.toast('Update Password', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Update Password Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
  },
}
</script>

<style>
[dir] .form-control:focus {
  border-color: #4D6D8A;
}
</style>
