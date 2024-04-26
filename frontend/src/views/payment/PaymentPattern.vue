<template>
  <div id="app">
    <section>
      <img src="../../assets/images/logo/full-logo.png" />
      <div class="formcontainer">
        <!-- amountPaid -->
        <div v-if="Number(amountPaid) == Number(amount)" class="container">
          Amount Fully Paid.
          <p>Thank you.</p>
        </div>
        <div
          v-if="Number(amountPaid) > parseFloat(Number(amount))"
          class="container"
        >
          Over Charged!!!
          <p><b>Seek Refund Now!</b></p>
        </div>
        <!-- v-if="Number(amountPaid) < Number(amount)" -->
        <div v-if="Number(amountPaid) < Number(amount)" class="container">
          <div class="payment-text">
            <h1><b>SELECT MODE OF PAYMENT</b></h1>
          </div>
          <div class="payment-text">
            <h5>
              <b>TO PAY</b>:

              <del style="text-decoration-style: double;">N</del>
              {{ amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}
            </h5>
          </div>

          <div>
            <div v-if="payment_mode != 'Installmental'">
              <div v-if="showInstallments === false">
                <b-button
                  v-b-modal.add-stock
                  class="mt-lg-0 mt-1 btn-block"
                  style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                  @click="oneOff()"
                >
                  ONE OFF
                </b-button>
              </div>
            </div>
            <div>
              <b-button
                v-b-modal.add-stock
                class="mt-lg-0 mt-1 btn-block"
                style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
                @click="installment()"
              >
                INSTALLMENTAL
              </b-button>
            </div>
          </div>
          {{ message }}
          <div v-if="showInstallments === true">
            <div>
              <h5><b>SELECT PAYMENT DURATION</b></h5>
            </div>
            <b-form-group>
              <label for="system-size" class="mb-lg-1 font-weight-bolder">
                Select Payment Duration
              </label>
              <b-form-select
                id="system-size"
                v-model="paymentDuration"
                :options="paymentDurations"
              />
            </b-form-group>
            <b-button
              v-b-modal.add-stock
              class="mt-lg-0 mt-1 btn-block"
              style="background-color: #4D6D8A !important; border-color: #4D6D8A !important;"
              @click="installmentProceed()"
            >
              PROCEED
            </b-button>
          </div>
          <br/>
        </div>
      </div>
    </section>
    <img style="max-width:200px;" alt="Vue logo" src="../../assets/pstk.png" />
  </div>
</template>

<script>
import paystack from "vue-paystack";
import { $themeConfig } from "@themeConfig";
import axios from "axios";
import { Route } from "vue-router";

export default {
  // name: 'App',
  components: {
    paystack,
  },
  data: () => ({
    message: null,
    place: "Enter Email",
    amount: 0,
    full_name: "okoli",
    email: "okoli@gmail.com",
    PUBLIC_KEY: "pk_live_1d8cf38825acf3a0c1bea9160837672f598e8488",
    paymentDuration: null,
    showInstallments: false,
    amountPaid: null,
    payment_mode: null,
    paymentDurations: [
      // { value: null, text: 'Please select a solar system size' },
      { value: "1", text: "1 month" },
      { value: "2", text: "2 months" },
      { value: "3", text: "3 months" },
      { value: "4", text: "4 months" },
    ],
  }),
  computed: {},
  mounted() {
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
    getUserData(projectId) {
      axios
        .get(`${process.env.VUE_APP_BASE_URL}/paymentrequest/${projectId}`)
        .then((response) => {
          const output = response.data.data;
          console.log(response, "sdjsdjjsjs");
          this.full_name = output.name;
          this.amount = parseFloat(output.amount).toFixed(2);
          this.email = output.email;
          this.amountPaid = output.amount_paid;
          this.payment_mode = output.mode_ofPayment;
          console.log(response, "sdjsdjjsjs");

          // const payments = [];
          // data.payments.map((payment) => {
          //   payments.push(Number(payment.amount));
          // });
          // const initialValue = 0;
          // const sumWithInitial = payments.reduce(
          //   (previousValue, currentValue) => previousValue + currentValue,
          //   initialValue
          // );

          // console.log(sumWithInitial, "ppplll");
          // // this.allAmountsPaid = "sumWithInitial"
          // this.allAmountsPaidIN = Math.trunc(sumWithInitial);

          if (
            output.mode_ofPayment == "Installmental" &&
            output.amount_paid < parseFloat(output.amount)
          ) {
            const data = {
              amountFieldEditable: true,
              amount: (output.amount - output.amount_paid).toFixed(2),
              duration: output.duration_of_payment,
              modeofpayment: output.mode_ofPayment,
              // typeofpayment: 2,
            };
            const paymentData = JSON.stringify(data);

            this.$router.push({
              path: `/payment/${this.$route.params.id}`,
              query: { paymentData },
              props: (route) => ({ query: route.query.q }),
            });
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    // processPayment: async (response) => {
    //   window.alert("Payment recieved");

    //   try {
    //     const out = await axios.get(
    //       `https://devapi.bartumenergy.com/api/confirmpayment/${
    //         response.reference
    //       }/${window.location.pathname.split("/")[2]}`
    //     );

    //     console.log(out);
    //     window.location.href = "/payment-success";
    //   } catch (err) {
    //     alert(err.response.data?.message);
    //     this.message = err.response.data?.message;
    //   }
    // },
    close: () => {
      console.log("You closed checkout page");
    },
    oneOff() {
      this.showInstallments = false;
      var amountT = Number(this.amount).toFixed(2);
      const data = {
        amountFieldEditable: false,
        amount: amountT,
        modeofpayment: "One-off",
        // typeofpayment: 1,
      };
      const paymentData = JSON.stringify(data);
      this.$router.push({
        path: `/payment/${this.$route.params.id}`,
        query: { paymentData },
        props: (route) => ({ query: route.query.q }),
      });
    },
    installment() {
      this.showInstallments = !this.showInstallments;
    },
    installmentProceed() {
      this.showInstallments = false;
      const data = {
        amountFieldEditable: true,
        amount: this.amount,
        duration: this.paymentDuration,
        modeofpayment: "Installmental",
        // typeofpayment: 2,
      };
      const paymentData = JSON.stringify(data);
      this.$router.push({
        path: `/payment/${this.$route.params.id}`,
        query: { paymentData },
        props: (route) => ({ query: route.query.q }),
      });
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
