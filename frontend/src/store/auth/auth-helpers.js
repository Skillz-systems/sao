export default {
  getLoginForm(form) {
    const formData = new FormData()
    formData.append('email', form.email)
    formData.append('password', form.password)
    return formData
  },
  getForgotPasswordForm(form) {
    const formData = new FormData()
    formData.append('email', form.email)
    return formData
  },
  getResetPasswordForm(form) {
    const formData = new FormData()
    formData.append('resetcode', form.resetCode)
    formData.append('password', form.password)
    return formData
  },
}
