export default{
  StoreNumber: (state , payload) => {
    state.user_number = payload
  },
  storeUserData: (state , payload) => {
    state.userData = payload
  },
  logout: (state) => {
    state.authenticated = false
    state.userData = {}
  },
  login: (state , payload) => {
    state.authenticated = true
    state.userData = payload
  },
  setRedirectRoute: (state , payload) => {
    state.redirectAfterLogin = payload
  },
}