<template class="payment-success-bg">
  <div id="app">
    <section>
      <img src="../../assets/images/logo/full-logo.png" />
      <h1 class="payment-text">
        PAYMENT SUCCESSFUL
      </h1>
      <img
        style="max-width:200px;"
        alt="Vue logo"
        src="../../assets/paymentsuccess.png"
      />
    </section>
  </div>
</template>

<script>
import { $themeConfig } from "@themeConfig";
import axios from "axios";
// import axios from 'axios'

export default {
  // name: 'App',
  components: {},
  data: () => ({
    place: "Enter Email",
    amount: 1000,
    full_name: "okoli",
    email: "okoli@gmail.com",
    PUBLIC_KEY: "pk_test_ea4fc8ff248670204ee30b660862643944448cbc",
  }),
  computed: {},
  mounted() {
    console.log(this.$route.params.id);
    this.getUserData(this.$route.params.id);
    // const { id } = $route.params
    // console.log(props, 'the component is now mounted.')
  },
  setup() {
    const { appName, appLogoImage } = $themeConfig.app;
    return {
      appName,
      appLogoImage,
    };
  },
  methods: {
    getUserData(projectId) {
      axios
        .get(`${process.env.VUE_APP_BASE_URL}/paymentrequest/${projectId}`)
        .then((response) => {
          this.full_name = response.data.data.name;
          this.amount = response.data.data.amount;
          this.email = response.data.data.email;
          console.log(response);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    processPayment: (response) => {
      window.alert("Payment recieved");

      //       message: "Approved"
      // redirecturl: "?trxref=T128945409055247&reference=T128945409055247"
      // reference: "T128945409055247"
      // status: "success"
      // trans: "1909305507"
      // transaction: "1909305507"
      // trxref: "T128945409055247
      console.log(response, "dksdsjks");
      const data = {
        reference: response.reference,
        name: this.name,
        itemid: this.$route.params.id,
      };
      axios
        .post(
          `${process.env.VUE_APP_BASE_URL}/confirmpayment/${this.$route.params.id}/${response.reference}`,
          data
        )
        .then((res) => {
          console.log(res, "ssjsjs");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    close: () => {
      console.log("You closed checkout page");
    },
  },
};
</script>

<style>
section {
  background-color: #ffffff;
  height: max-content;
  padding: 1rem 2rem;
  padding-top: 2rem;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  /* background-color: #ffffff; */
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
.payment-success-bg {
  /* background-color: red; */
}

.formcontainer {
  text-align: left;
  margin: 24px 50px 12px;
}
.container {
  padding: 16px 0;
  text-align: left;
  /* box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; */
  /* box-shadow: 0 0 10px 5px #ccc; */
  padding: 2rem 3rem;
}
span.psw {
  float: right;
  padding-top: 0;
  padding-right: 15px;
}

.payment-text {
  margin-top: 30px;
}
/* Change styles for span on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
    display: block;
    float: none;
  }
}
</style>
