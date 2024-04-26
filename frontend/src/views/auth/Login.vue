<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">
      <!-- Left Text-->
      <b-col
        id="login-left"
        lg="5"
        class="d-none d-lg-flex align-items-center p-5"
      >
        <div class="w-100 d-lg-block align-items-center justify-content-center" 
    
        >
       
    
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col
        lg="7"
        class="align-items-center justify-center auth-bg px-2 p-lg-5"
      >
     
   
        <b-col
          sm="8"
          md="6"
          lg="12"
          class="px-xl-5 px-lg-5 mx-auto"
        >

        <div style="width: 300px, background-color: red"  class="login-c-logo">  
     
     <b-img
         :src="fullLogo"
         class="ml-1"
         style="width: 10%; height: 10%;margin-bottom: 15px;"
       />
     </div>
          <b-img
            width="200"
            center
            :src="fullLogo"
            class="d-lg-none mt-n4 mb-4"
          />
          <b-card-title
            title-tag="h2"
            class="font-weight-bold text-center mb-1"
            style="color: #4D6D8A"
          >
            Welcome Back
          </b-card-title>
          <!-- {{ process.env }} -->
          <!-- <b-card-text class="mb-2 text-center">
            <h4>Sign In</h4>
          </b-card-text> -->

          <!-- form -->
          <validation-observer ref="loginValidation">
            <b-form
              class="auth-login-form mt-3"
              @submit.prevent
            >
              <!-- email -->
              <b-form-group
                label="Your Email"
                label-for="login-email"
              >
                <validation-provider
                  #default="{ errors }"
                  name="email"
                  rules="required"
                >
                  <b-form-input
                    id="login-email"
                    v-model="userEmail"
                    :state="errors.length > 0 ? false:null"
                    name="login-email"
                    placeholder="e.g. johndoe@email.com"
                    class="mt-1"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>

              <!-- forgot password -->
              <b-form-group>
                <div class="mb-1 mt-2">
                  <label for="login-password">Your Password</label>
                </div>
                <validation-provider
                  #default="{ errors }"
                  name="password"
                  rules="required"
                >
                  <b-input-group
                    class="input-group-merge"
                    :class="errors.length > 0 ? 'is-invalid':null"
                  >
                    <b-form-input
                      id="login-password"
                      v-model="password"
                      :state="errors.length > 0 ? false:null"
                      class="form-control-merge"
                      :type="passwordFieldType"
                      name="login-password"
                      placeholder="Password"
                    />
                    <b-input-group-append is-text>
                      <feather-icon
                        class="cursor-pointer"
                        :icon="passwordToggleIcon"
                        @click="togglePasswordVisibility"
                      />
                    </b-input-group-append>
                  </b-input-group>
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
                @click="login"
              >
                Sign in
              </b-button>
              <b-button
                v-if="isLoading"
                class="mt-3 mb-1"
                block
                style="border-radius: 20px; background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                disabled
              >
                Signing in...
              </b-button>
              <b-link
                style="color: #C4C4C4"
                to="/forgot-password"
              >
                <small>Forgot Password?</small>
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
      status: '',
      password: '',
      userEmail: '',
      sideImg: require('@/assets/images/pages/login-3.png'),
      fullLogo: require('@/assets/images/logo/full-logo.png'),
      tagLine: require('@/assets/images/logo/tagline.svg'),
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
    login() {
      this.isLoading = true
      this.$refs.loginValidation.validate().then(success => {
        if (success) {
          const form = {
            email: this.userEmail,
            password: this.password,
          }
          this.$store.dispatch('auth/login', { form }).then(res => {
            this.isLoading = false
            const { message } = res.data
            this.$router.push('/').then(() => {
              this.toast('Welcome', 'LogInIcon', message, 'success')
            })
          }).catch(err => {
            this.isLoading = false
            this.toast('Login Attempt', 'LogInIcon', err.response.data.message, 'danger')
          })
        } else {
          this.isLoading = false
          this.toast('Login Attempt', 'LogInIcon', 'You must fill in all required fields', 'warning')
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
  .login-c-logo{
    /* background-color: red; */
    display: flex;
    justify-content: center;
  }
  .justify-center{
    justify-content: center;
    /* border: 2px solid red; */
    display: flex;
    /* flex-direction: column; */
  }
</style>
