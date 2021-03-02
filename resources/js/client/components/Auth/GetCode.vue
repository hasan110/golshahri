<template>
  <div class="">
    <div class="container" style="margin-top:50px">
      <div class="row">
        <div class="col-md-8 mx-auto my-3">
          <div style="height: auto;" class="card">
            <div class="card-header">وارد کردن کد تایید</div>
            <div class="card-body">
                <div class="form-group">
                  <label for="code">کد تایید</label>
                  <input v-model="formdata.code" @input="InsertJustNumber" type="number" class="form-control" id="code" placeholder="کد تایید دریافتی را وارد کنید">
                </div>
                <button @click="verifyCode" class="btn btn-outline-success">تایید</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div v-for="numbererror in errors.code" :key="numbererror">{{ numbererror }}</div>
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
import { mapState , mapActions } from 'vuex';
export default {
  name:"GetCode",
  data(){
    return {
      formdata:{
        code:'',
        number:'',
      },
      errors:{
        code:null,
      },
      errorSnackbar:false,
      loginError:false,
      loginErrorMessage:'',
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
    verifyCode(){
      this.loading = true;
      Axios.post(`verifyCode` , this.formdata)
      .then(res => {
        if(res.data.status == 'old'){
          const token = res.data.data.auth_token;
          localStorage.setItem('auth_token' , token);
          this.login(res.data.data);
          this.$router.push(this.redirectAfterLogin);
        }else{
          this.$router.push('/Register');
        }
      })
      .catch(err => {
        this.loading = false;
        if(err.response.data.error){
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
    if(!this.user_number){
      this.$router.push('/Home');
    }
    this.formdata.number = this.user_number;
  }
}
</script>
<style scoped>

</style>