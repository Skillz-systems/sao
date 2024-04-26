<template class="payment-success-bg">
  <div id="app">
    <section>
      <img src="../../assets/images/logo/full-logo.png">
      <h1 class="payment-text">
        PAYMENT SUCCESSFUL
      </h1>
      <img
        style="max-width:200px;"
        alt="Vue logo"
        src="../../assets/paymentsuccess.png"
      >

    </section>

  </div>
</template>

<script>
import { $themeConfig } from '@themeConfig'

export default {
  setup() {
    const { appName, appLogoImage } = $themeConfig.app
    return {

      appName,
      appLogoImage,
    }
  },
}
</script>

<style>

section{
  background-color: #ffffff;
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

</style>
