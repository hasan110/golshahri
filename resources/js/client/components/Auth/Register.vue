<template>
  <div class="">
    <div class="container" style="margin-top:50px">
      <div class="row">
        <div class="col-md-8 mx-auto my-3">
          <div style="height: auto;" class="card">
            <div class="card-header">ثبت نام</div>
            <div class="card-body">
              <div class="form-group">
                <label for="name">نام و نام خانوادگی</label>
                <input v-model="formdata.name" class="form-control" id="name" placeholder="نام و نام خانوادگی را وارد کنید">
              </div>
              <div class="form-group">
                <label for="number">شماره تلفن</label>
                <input disabled v-model="user_number" class="form-control" id="number" placeholder="شمار تلفن همراه خود را وارد کنید">
              </div>
              <button @click="Register" class="btn btn-outline-success">ثبت نام</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div v-if="loading" class="appLoading">
      <div class="circles-wrapper">
        <div></div><div></div><div></div><div></div>
      </div>
    </div>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div v-for="nameerror in errors.name" :key="nameerror">{{ nameerror }}</div>
      <div v-for="numbererror in errors.number" :key="numbererror">{{ numbererror }}</div>
      <br>
      <div class="text-center text-light">
          <h1 class="text-center">
          <i class="fa fa-info-circle"></i>
          </h1>
      </div>
    </v-snackbar>

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import { mapState , mapActions } from 'vuex';
export default {
  name:"Register",
  data(){
    return {
      formdata:{
        name:'',
        number:this.user_number
      },
      errors:{
        name:null,
        number:null,
      },
      errorSnackbar:false,
      loading:false
    }
  },
  computed:{
    ...mapState([
      'user_number',
      'redirectAfterLogin'
    ])
  },
  methods:{
    ...mapActions([
      'login'
    ]),
    Register(){
      this.loading = true;
      Axios.post(`register` , this.formdata)
      .then(res => {
        const token = res.data.data.auth_token;
        localStorage.setItem('auth_token' , token);
        this.login(res.data.data);
        this.$router.push(this.redirectAfterLogin);
      })
      .catch(err => {
        this.loading = false;
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    }
  },
  beforeMount(){
    if(localStorage.getItem('auth_token') !== null){
      this.$router.push('/Login ');
    }
    if(!this.user_number){
      this.$router.push('/Home');
    }
    this.formdata.number = this.user_number;
  }
}
</script>
<style scoped>

</style>