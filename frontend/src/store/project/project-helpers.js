export default {
  getAddProjectForm(form) {
    const formData = new FormData()
    formData.append('projectname', form.projectName)
    formData.append('projecttype', form.projectType)
    formData.append('solarsystemsize', form.systemSize)
    formData.append('numberofpanels', form.solarPanelQuantity)
    formData.append('numberofbatteries', form.batteryQuantity)
    formData.append('description', form.description)
    formData.append('installationtype', form.installationType)
    formData.append('status', form.status)
    formData.append('clientid', form.client)
    formData.append('lgaid', form.lga)
    formData.append('productid', form.product)
    formData.append('price', form.price)
    formData.append('numberofinverters', form.inverterQuantity)
    formData.append('batterytypeid', form.batteryType)
    formData.append('invertertypeid', form.inverterType)
    formData.append('solarpaneltypeid', form.solarPanelType)
    formData.append('address', form.address)
    formData.append('discount', form.discount)
    formData.append('miscellaneous', form.miscellaneous)
    formData.append('project_selection_mode', form.project_selection_mode)

    return formData
  },
  getAddMeshGridForm(form) {
    const formData = new FormData()
    formData.append('product_id', form.product)
    formData.append('max_connection', form.connection)
    formData.append('clientid', form.client)
    formData.append('log', form.log)
    formData.append('lat', form.lat)
    formData.append('full_address', form.address)
    formData.append('state', form.state)
    formData.append('lga', form.lga)
  
    return formData
  },
  getSendMessageForm(form) {
    const formData = new FormData()
    formData.append('projectid', form.projectID)
    formData.append('message', form.message)
    formData.append('subject', form.subject)
    return formData
  },
  getEditProjectForm(form) {
    const formData = new FormData()
    formData.append('id', form.projectID)
    formData.append('projectname', form.projectName)
    formData.append('projecttype', form.projectType)
    formData.append('solarsystemsize', form.systemSize)
    formData.append('numberofpanels', form.solarPanelQuantity)
    formData.append('numberofbatteries', form.batteryQuantity)
    formData.append('description', form.description)
    formData.append('installationtype', form.installationType)
    formData.append('status', form.status)
    formData.append('clientid', form.client)
    formData.append('lgaid', form.lga)
    formData.append('productid', form.product)
    formData.append('price', form.price)
    formData.append('numberofinverters', form.inverterQuantity)
    formData.append('batterytypeid', form.batteryType)
    formData.append('invertertypeid', form.inverterType)
    formData.append('solarpaneltypeid', form.solarPanelType)
    return formData
  },
  getDeleteProjectForm(form) {
    const formData = new FormData()
    formData.append('id', form.projectID)
    return formData
  },

  getAddMeshConnectionForm(form) {
    const formData = new FormData()
    formData.append('discount', form.discount)
    formData.append('client_id', form.clientId)
    formData.append('log', form.log)
    formData.append('lat', form.lat)
    formData.append('address_description', form.address_description)
    formData.append('state_id', form.state_id)
    formData.append('lgas_id', form.lgas_id)
    formData.append('meshgrid_id', form.meshgridId)
    formData.append('installation_price', form.installation_price)
    formData.append('order_description', form.order_description)


  
    return formData
  },
}
