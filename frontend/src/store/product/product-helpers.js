export default {
  getAddProductForm(form) {
    const formData = new FormData()
    formData.append('productname', form.productName)
    formData.append('numberofpanels', form.solarPanelQuantity)
    formData.append('numberofbatteries', form.batteryQuantity)
    formData.append('numberofinverters', form.inverterQuantity)
    formData.append('description', form.description)
    formData.append('solarpaneltypeid', form.solarpaneltypeid)
    formData.append('invertertypeid', form.invertertypeid)
    formData.append('batterytypeid', form.batterytypeid)
    formData.append('numberofinverters', form.inverterQuantity)
    formData.append('accessories', form.accessories)
    formData.append('productDiscount', form.productDiscount)
    formData.append('type', form.type)
   
    

    return formData
  },
  getEditProductForm(form) {
    const formData = new FormData()
    formData.append('id', form.productID)
    formData.append('productname', form.productName)
    formData.append('numberofpanels', form.solarPanelQuantity)
    formData.append('numberofbatteries', form.batteryQuantity)
    formData.append('description', form.description)
    formData.append('price', form.price)
    formData.append('solarpaneltypeid', form.solarPanelType)
    formData.append('invertertypeid', form.inverterType)
    formData.append('batterytypeid', form.batteryType)
    formData.append('numberofinverters', form.inverterQuantity)
    return formData
  },
  getDeleteProductForm(form) {
    const formData = new FormData()
    formData.append('id', form.productID)
    return formData
  },
}
