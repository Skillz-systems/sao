export default {
  getAddTeamMember(form) {
    const formData = new FormData()
    formData.append('email', form.email)
    formData.append('fullname', form.name)
    return formData
  },
  getUpdateTeamMember(form) {
    const formData = new FormData()
    formData.append('id', form.id)
    formData.append('phone', form.phone)
    formData.append('role', form.role)
    formData.append('fullname', form.name)
    return formData
  },
  getUpdatePassword(form) {
    const formData = new FormData()
    formData.append('currentpassword', form.currentPassword)
    formData.append('newpassword', form.newPassword)
    return formData
  },
  getUpdateProfilePicture(form) {
    const formData = new FormData()
    formData.append('profilepicture', form.profilePicture)
    return formData
  },
}
