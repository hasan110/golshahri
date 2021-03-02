<template>
  <div class="">
    <div class="container" style="margin-top:50px">
      <div class="row">
        <div class="col-md-8 mx-auto my-3">
          <div style="height: auto;" class="card">
            <div class="card-header">ورود</div>
            <div class="card-body">
                <div class="form-group">
                  <label for="number">شماره تلفن</label>
                  <input v-model="formdata.number" @input="InsertJustNumber" class="form-control" type="number" id="number" placeholder="شماره تلفن همراه خود را وارد کنید">
                </div>
                <button @click="SignIn" class="btn btn-outline-success">تایید</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div v-for="numbererror in errors.number" :key="numbererror">{{ numbererror }}</div>
      <br>
      <div class="text-center text-light">
          <h1 class="text-center">
          <i class="fa fa-info-circle"></i>
          </h1>
      </div>
    </v-snackbar>

    <v-snackbar color="red" :timeout="4000" v-model="loginError">
      <div>{{ loginErrorMessage }}</div>
      <br>
      <div class="text-center text-light">
          <h1 class="text-center">
          <i class="fa fa-info-circle"></i>
          </h1>
      </div>
    </v-snackbar>

    <div v-if="loading" class="appLoading">
      <div class="circles-wrapper">
        <div></div><div></div><div></div><div></div>
      </div>
    </div>

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import { mapActions } from 'vuex';
export default {
  name:"Login",
  data(){
    return {
      formdata:{
        number:'',
      },
      errors:{
        number:null,
      },
      errorSnackbar:false,
      loginError:false,
      loginErrorMessage:'',
      loading:false
    }
  },
  methods:{
    ...mapActions([
      'StoreNumber'
    ]),
    SignIn(){
      this.loading = true;
      Axios.post(`sendCode` , this.formdata)
      .then(res => {
        this.StoreNumber(this.formdata.number);
        this.$router.push('/GetCode');
      })
      .catch(err => {
        this.loading = false;
        if(err.response.data.status){
          this.loginErrorMessage = err.response.data.message;
          this.loginError = true;
        }
        if(err.response.data.errors){
          this.errors = err.response.data.errors;
          this.errorSnackbar = true;
        }
      });
    },
    InsertJustNumber(e){
      const isNumeric = /^[-+]?(\d+|\d+\.\d*|\d*\.\d+)$/;
      const value = e.target.value;
      if(!isNumeric.test(value)){
        e.target.value = ''
      }
    }
  },
  beforeMount(){
    if(localStorage.getItem('auth_token') !== null){
      this.$router.push('/Home');
    }
  }
}
</script>
<style scoped>

</style>