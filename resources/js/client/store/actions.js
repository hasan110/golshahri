export default {
  StoreNumber : ({commit} , payload) => {
    commit('StoreNumber' , payload)  
  },
  storeUserData : ({commit} , payload) => {
    commit('storeUserData' , payload)  
  },
  logout : ({commit}) => {
    commit('logout')  
  },
  login : ({commit} , payload) => {
    commit('login' , payload)
  },
  setRedirectRoute : ({commit} , payload) => {
    commit('setRedirectRoute' , payload)
  }
}