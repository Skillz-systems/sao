<template>
  <div id="app">
    <section>
      <img src="../../assets/images/logo/full-logo.png" />

      <b-tabs content-class="mt-3">
        <b-tab title="Card" active>
          <Fragment>
            <div class="formcontainer">
              <div class="container">
                <div class="payment-text">
                  <h1><b>MAKE PAYMENT</b></h1>
                  {{ amount }}
                </div>
                <div
                  v-if="paymentData.amountFieldEditable === true"
                  class="payment-text"
                >
                  <h3>Installmental Payment</h3>
                </div>

                <div
                  v-if="paymentData.amountFieldEditable === false"
                  class="payment-text"
                >
                  <h3>One Off Payment</h3>
                </div>

                <div class="payment-text">
                  <h5>
                    <b>TOTAL AMOUNT:</b>
                    <del style="text-decoration-style: double;">N</del>
                    {{
                      paymentData.amount
                        .toString()
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }}
                  </h5>
                </div>
                {{ message }}
                <label for="uname">
                  <strong>Full Name</strong>
                </label>
                <input
                  v-model="full_name"
                  required
                  type="text"
                  placeholder="John Doe"
                  name="uname"
                  disabled
                />
                <label for="email">
                  <strong>Email Address</strong>
                </label>
                <input
                  v-model="email"
                  :placeholder="place"
                  required
                  type="text"
                  name="email"
                  width="40px"
                  disabled
                />
                <label for="amount">
                  <strong>Amount</strong>
                </label>
                <div v-if="paymentData.amountFieldEditable === false">
                  <input
                    v-model.number="paymentData.amount"
                    type="number"
                    placeholder="amount"
                    name="amount"
                    required
                    disabled
                  />
                </div>
                <div v-if="paymentData.amountFieldEditable === true">
                  <input
                    v-model.number="amount"
                    type="number"
                    placeholder="amount"
                    name="amount"
                    required
                  />
                </div>

                <br />
                <br />

                <div
                  v-if="
                    (amount >= paymentData.amount * 0.4 ||
                      JSON.parse(amount) === JSON.parse(paymentData.amount)) &&
                      amount <= paymentData.amount
                  "
                >
                  {{ amount }}
                  <paystack
                    :amount="parseInt(amount) * 100"
                    :email="email"
                    paystackkey="pk_live_1d8cf38825acf3a0c1bea9160837672f598e8488"
                    :callback="processPayment"
                    :close="close"
                    :embed="false"
                  >
                    <i class="fas fa-money-bill-alt" />
                    Make Payment
                  </paystack>
                </div>
                <div
                  v-if="amount / 0.4 < paymentData.amount"
                  style="background-color: red; color: white;display: flex;justify-content: center;"
                >
                  Amount Less than 40%
                </div>
                <div
                  v-if="amount > paymentData.amount"
                  style="background-color: red; color: white;display: flex;justify-content: center;"
                >
                  Amount Exceeds Project Cost
                </div>
              </div>
            </div>
            <img
              style="max-width:200px;"
              alt="Vue logo"
              src="../../assets/pstk.png"
            />
          </Fragment>
        </b-tab>
        <b-tab title="Transfer">
          <Fragment>
            <div v-if="paymentData.amountFieldEditable === true">
              Pay a minimum <del style="text-decoration-style: double;">N</del>
              {{
                (paymentData.amount * 0.4)

                  .toString()
                  .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              }}
              to this account no:
            </div>
            <div v-if="paymentData.amountFieldEditable === false">
              Pay a sum of <del style="text-decoration-style: double;">N</del>
              {{
                paymentData.amount
                  .toString()
                  .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              }}
              to this account no
            </div>
          </Fragment>
        </b-tab>
      </b-tabs>
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
  props: {
    data: { type: Array, default: () => [] },
  },
  data: () => ({
    payment_mode: null,
    message: null,
    place: "Enter Email",
    amount: 0,
    full_name: "",
    email: "",
    paymentData: [],
    PUBLIC_KEY: "pk_live_1d8cf38825acf3a0c1bea9160837672f598e8488",
  }),
  computed: {},
  mounted() {
    this.getUserData(this.$route.params.id);
    const { paymentData } = this.$route.query;

    console.log(paymentData, "askjaaoai");

    const ParsePaymentData = JSON.parse(paymentData);
    this.paymentData = JSON.parse(paymentData);
    console.log(ParsePaymentData, "sdjsiiikjs");
    this.amount = ParsePaymentData.amount;
    this.payment_mode = paymentData.modeofpayment;
    localStorage.setItem("paymentData", paymentData);
  },
  created() {
    const { paymentData } = this.$route.query;
    this.paymentData = JSON.parse(paymentData);
    // this.amount = this.paymentData.amount;
    localStorage.setItem("paymentData", paymentData);
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
          // (this.amount = response.data.data.amount)
          this.email = response.data.data.email;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    processPayment: async (response) => {
      console.log(response, "sdjsdjs");
      window.alert("Payment recieved");

      const paymentDetails = localStorage.getItem("paymentData", paymentData);

      const paymentData = JSON.parse(paymentDetails);
      console.log(paymentDetails, paymentData);

      const form = new FormData();
      form.append("paymentduration", paymentData.duration);
      form.append("modeofpayment", paymentData.modeofpayment);
      form.append("typeofpayment", 1);
      // formData.append("email", this.email);

      console.log(form, "hdhdhd");

      try {
        const respon = await axios.post(
          `${process.env.VUE_APP_BASE_URL}/confirmpayment/${
            response.reference
          }/${window.location.pathname.split("/")[2]}`,
          form
        );

        window.open("/payment-success");
      } catch (err) {
        console.log(err, "djdsjsj");
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
