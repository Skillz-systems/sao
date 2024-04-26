export default {
  getAddClientForm(form) {
    const formData = new FormData()
    formData.append('email', form.email)
    formData.append('phone', form.phone)
    formData.append('clientname', form.clientName)
    formData.append('clienttype', form.clientType)
    formData.append('load', form.load)
    formData.append('housesize', form.houseSize)
    formData.append('address', form.address)
    formData.append('lgaid', form.lga)
    formData.append('stateid', form.state)
    return formData
  },
  getEditClientForm(form) {
    const formData = new FormData()
    formData.append('id', form.clientID)
    formData.append('phone', form.phone)
    formData.append('clientname', form.clientName)
    formData.append('clienttype', form.clientType)
    formData.append('load', form.load)
    formData.append('housesize', form.houseSize)
    formData.append('address', form.address)
    formData.append('lgaid', form.lga)
    formData.append('stateid', form.state)
    return formData
  },
}
