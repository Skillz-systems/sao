<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">
      <!-- Left Text-->
      <b-col
        id="login-left"
        lg="5"
        class="d-none d-lg-flex align-items-center p-5"
      >
        <div class="w-100 d-lg-block align-items-center justify-content-center">
          <b-img
            :src="fullLogo"
            class="ml-1"
            style="width: 80%; height: 100%;"
          />
          <!-- <b-img
            :src="tagLine"
            class="mt-3"
          /> -->
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col
        lg="7"
        class="d-flex align-items-center auth-bg px-2 p-lg-5"
      >
        <b-col
          sm="8"
          md="6"
          lg="12"
          class="px-xl-5 px-lg-5 mx-auto"
        >
          <b-img
            width="200"
            center
            :src="fullLogo"
            class="d-lg-none mt-n4 mb-4"
          />
          <b-img
            width="100"
            center
            :src="forgotPasswordIcon"
            class="mb-4"
          />
          <b-card-title
            title-tag="h2"
            class="font-weight-bold text-center mb-1"
            style="color: #4D6D8A"
          >
            Did you forget your password?
          </b-card-title>
          <b-card-text class="mb-2 text-center">
            <h4>No worries! Enter your email and we will send you a reset link.</h4>
          </b-card-text>

          <!-- form -->
          <validation-observer ref="forgotPasswordValidation">
            <b-form
              class="auth-login-form mt-3"
              @submit.prevent
            >
              <!-- email -->
              <b-form-group>
                <validation-provider
                  #default="{ errors }"
                  name="email"
                  rules="required"
                >
                  <label
                    for="login-email"
                    class="mb-lg-1 font-weight-bolder"
                  >
                    Your Email
                  </label>
                  <b-form-input
                    id="login-email"
                    v-model="userEmail"
                    :state="errors.length > 0 ? false:null"
                    name="login-email"
                    placeholder="e.g. johndoe@email.com"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- submit buttons -->
              <b-button
                v-if="!isLoading"
                class="mt-3 mb-1"
                type="submit"
                variant="primary"
                block
                style="border-radius: 20px; background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                @click="sendResetLink"
              >
                Send Request
              </b-button>
              <b-button
                v-if="isLoading"
                class="mt-3 mb-1"
                block
                style="border-radius: 20px; background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                disabled
              >
                Sending...
              </b-button>
              <b-link
                to="/login"
                style="color: #C4C4C4"
              >
                <small>Back to login</small>
              </b-link>
            </b-form>
          </validation-observer>
        </b-col>
      </b-col>
      <!-- /Login-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require,vue/no-unused-components */
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email } from '@validations'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      isLoading: false,
      userEmail: '',
      sideImg: require('@/assets/images/pages/login-3.png'),
      fullLogo: require('@/assets/images/logo/full-logo.png'),
      tagLine: require('@/assets/images/logo/tagline.svg'),
      forgotPasswordIcon: require('@/assets/images/logo/forgot-password-icon.svg'),
      // validation rulesimport store from '@/store/index'
      required,
      email,
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  methods: {
    sendResetLink() {
      this.isLoading = true
      this.$refs.forgotPasswordValidation.validate().then(success => {
        if (success) {
          const form = {
            email: this.userEmail,
          }
          this.$store.dispatch('auth/getResetLink', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.$router.push('/login').then(() => {
              this.toast('Send Reset Link', 'CheckCircleIcon', message, 'success')
            })
          }).catch(err => {
            this.isLoading = false
            this.toast('Send Reset Link Attempt', 'SlashIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Send Reset Link Attempt', 'AlertTriangleIcon', 'You must fill in all required fields', 'warning')
        }
      })
    },
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/pages/page-auth.scss';
</style>

<style scoped>
#login-left {
  background-image: url("../../assets/images/pages/login-3.svg");
  background-repeat: no-repeat;
  /*background-attachment: fixed;*/
  background-position: center;
  background-size: cover;
}
.form-control:focus {
  border-color: #4D6D8A !important;
}
[dir] .btn-primary:hover:not(.disabled):not(:disabled) {
  box-shadow: 0 8px 25px -8px #4D6D8A;
}
[dir] .input-group:not(.bootstrap-touchspin):focus-within .form-control, [dir] .input-group:not(.bootstrap-touchspin):focus-within .input-group-text {
  border-color: #4D6D8A !important;
}
</style>
