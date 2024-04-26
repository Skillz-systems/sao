<template>
  <div id="app">
    <section>
      <img src="../../assets/images/logo/full-logo.png" />

      <div class="formcontainer">
        <div class="container">
          <div class="payment-text">
            <h1><b>UPDATE PASSWORD</b></h1>
          </div>
          {{ message }}
          <label for="uname">
            <strong>Password</strong>
          </label>
          <input
            v-model="password"
            required
            type="text"
            placeholder="password"
            name="password"
            @change="updatePassword"
          />
          <label for="email">
            <strong>Confirm Password</strong>
          </label>
          <input
            v-model="password2"
            placeholder="password 2"
            required
            type="text"
            name="email"
            width="40px"
            @change="updatePassword2"
          />
          <!-- <label for="amount">
            <strong>Amount</strong>
          </label>
          <input
            v-model.number="amount"
            type="number"
            placeholder="1000"
            name="amount"
            required
            disabled
          > -->
          <br />
          <br />
          <b-button
            v-if="!isLoading"
            type="submit"
            style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
            @click="submitPassword"
          >
            Save Changes
          </b-button>
          <b-button
            v-if="isLoading"
            disabled
            style="background-color: #4D6D8A !important; border-color: #4D6D8A !important; border-radius: 40px;"
          >
            Saving...
          </b-button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import paystack from "vue-paystack";
import { $themeConfig } from "@themeConfig";
import axios from "axios";

export default {
  // name: 'App',
  components: {
    paystack,
  },
  data: () => ({
    message: null,
    loading: false,
    password: null,
    password2: null,
    amount: 1000,
    full_name: "okoli",
    email: "okoli@gmail.com",
    PUBLIC_KEY: "pk_live_1d8cf38825acf3a0c1bea9160837672f598e8488",
  }),
  computed: {},
  mounted() {
    console.log(this.$route.params.id);
    this.getUserData(this.$route.params.id);
  },
  setup() {
    const { appName, appLogoImage } = $themeConfig.app;
    return {
      appName,
      appLogoImage,
    };
  },
  methods: {
    updatePassword(e) {
      const password = e.target.value;
      this.password = password;
    },
    updatePassword2(e) {
      const password2 = e.target.value;
      this.password2 = password2;
    },

    submitPassword() {
      // if()

      if (this.password && this.password2) {
        if (this.password === this.password2) {
          /////api
        } else {
          this.toast("passwords not equal");
        }
      } else {
        this.toast("password fields cannot be empty");
        // alert("password not equal");
      }
    },
    getUserData(projectId) {
      axios
        .get(`${process.env.VUE_APP_BASE_URL}/paymentrequest/${projectId}`)
        .then((response) => {
          this.full_name = response.data.data.name;
          (this.amount = response.data.data.amount)(
            (this.email = response.data.data.email)
          );
          console.log(response);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    processPayment: async (response) => {
      window.alert("Payment recieved");

      console.log(response, "dksdsjks");

      try {
        const out = await axios.get(
          `${process.env.VUE_APP_BASE_URL}/confirmpayment/${
            response.reference
          }/${window.location.pathname.split("/")[2]}`
        );

        console.log(out);
        window.location.href = "/payment-success";
      } catch (err) {
        alert(err.response.data?.message);
        this.message = err.response.data?.message;
      }
    },
    close: () => {
      console.log("You closed checkout page");
    },
  },
};
</script>

<style>
hr {
  /* margin-bottom: 2rem; */
  text-align: center;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
  margin: 2rem 10rem;
}
input {
  width: 100%;
  padding: 16px 8px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}
button {
  background-color: #8ebf42;
  color: white;
  padding: 14px 0;
  margin: 10px 0;
  border: none;
  cursor: grabbing;
  width: 100%;
}
button:hover {
  opacity: 0.8;
}
.formcontainer {
  text-align: left;
  margin: 24px 50px 12px;
}

.payment-text {
  text-align: center;
}
.container {
  padding: 16px 0;
  text-align: left;
  /* box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; */
  box-shadow: 0 0 10px 5px #ccc;
  padding: 2rem 3rem;
}
span.psw {
  float: right;
  padding-top: 0;
  padding-right: 15px;
}
/* Change styles for span on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
    display: block;
    float: none;
  }
}

@media screen and (max-width: 1000px) {
  #app {
    margin: 2rem 3rem;
  }
}
@media screen and (max-width: 525px) {
  #app {
    margin: 2rem 0rem;
  }
}
</style>
