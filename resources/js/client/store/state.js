var authenticated = false;
var userData = {};

if(localStorage.getItem('auth_token') === null){
  authenticated = false;
}else{
  authenticated = true;
}

export default {
  authenticated,
  user_number:null,
  userData,
  redirectAfterLogin:'/Home'
}