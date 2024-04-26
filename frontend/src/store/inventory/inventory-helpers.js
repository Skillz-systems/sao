export default {
  getAddStockForm(form) {
    const formData = new FormData()
    formData.append('itemid', form.itemID)
    formData.append('subitemid', form.subItemID)
    formData.append('quantity', form.quantity)
    formData.append('capacity', form.capacity)
    formData.append('rating', form.rating)
    formData.append('name', form.productName)
    formData.append('price', form.price)
    formData.append('stockid', form.productSerialNumber)
    formData.append('status', form.status)
    return formData
  },
  getAddSubItemForm(form) {
    const formData = new FormData()
    formData.append('name', form.subItem)
    formData.append('itemid', form.item)
    formData.append('referencenumber', form.refNo)
    formData.append('hasb3', form.hasb3)
    // status 0 implies product is available
    formData.append('status', 0)
    return formData
  },
  getEditSubItemForm(form) {
    const formData = new FormData()
    formData.append('id', form.subItemID)
    formData.append('name', form.subItem)
    formData.append('itemid', form.item)
    formData.append('referencenumber', form.refNo)
    formData.append('hasb3', form.hasb3)
    return formData
  },
  getEditStockForm(form) {
    const formData = new FormData()
    formData.append('itemid', form.itemID)
    formData.append('subitemid', form.subItemID)
    formData.append('quantity', form.quantity)
    formData.append('capacity', form.capacity)
    formData.append('rating', form.rating)
    formData.append('name', form.productName)
    formData.append('price', form.price)
    formData.append('stockid', form.stockid)
    formData.append('status', form.status)
    return formData
  },
}
