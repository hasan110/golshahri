<template>
  <div>
    <nav class="navbar">
      <router-link class="navbar-brand" :to="{name:'Home'}">
        <div class="logo"><img :src="ImageUrl+logo_image" alt=""></div>
      </router-link>
      <ul class="navbar-items">
        <li class="nav-item"><router-link class="nav-link" :to="{name:'Home'}">خانه</router-link></li>
        <li class="nav-item dropdown">
          <button class="nav-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              کاربری <i class="fa fa-sort-down"></i>
          </button>
          <div class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton">
            <router-link v-if="!authenticated" class="dropdown-item pl-1" :to="{name:'Login'}">ورود</router-link>
            <router-link class="dropdown-item pl-1" :to="{name:'MyAdvertises'}">آگهی های من</router-link>
            <div v-if="authenticated" class="dropdown-item pl-1">{{userData.name}}</div>
            <div v-if="authenticated" class="dropdown-item pl-1">{{userData.number}}</div>
            <button v-if="authenticated" class="btn btn-danger btn-sm btn-block" @click="logoutdialog = true">خروج</button>
          </div>
        </li>
        <li class="nav-item nav-left-btn">
        </li>
      </ul>
      <router-link title="ثبت آگهی" class="btn btn-legendary advertise-btn" :to="{name:'CreateAdvertise'}">ثبت آگهی</router-link>
    </nav>

    <v-dialog v-model="logoutdialog" max-width="340">
      <v-card>
        <v-card-title class="headline">
          خروج
        </v-card-title>
        <v-card-text>آیا مطمئنید می خواهید خارج شوید ؟</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="logoutdialog = false"
          >
            خیر
          </v-btn>
          <v-btn
            color="green darken-1"
            text
            @click="signOut"
          >
            بله
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import Axios from '../Services/httpRequest/axios'
import { mapState , mapActions } from 'vuex'
export default {
  name:'NavBar',
  data(){
    return {
      logoutdialog:false,
      logo_image:''
    }
  },
  computed:{
    ...mapState([
      'authenticated',
      'userData'
    ])
  },
  methods: {
    ...mapActions([
      'logout',
      'storeUserData'
    ]),
    signOut(){
      localStorage.removeItem('auth_token');
      this.logout();
      this.logoutdialog = false;
      this.$router.push('/Home');
    },
    getSettings(){
      Axios.get(`getSettings`)
      .then(res => {
          this.logo_image = res.data.logo_image;
      })
      .catch(err => {
          console.log(err)
      });
    }
  },
  mounted(){
    this.getSettings();
  },
  beforeCreate(){
    if(localStorage.getItem('auth_token') !== null){
      const auth_token = localStorage.getItem('auth_token');
      Axios.post(`getUserData` , {auth_token})
      .then(async res => {
        this.storeUserData(res.data);
      })
      .catch(err => {
        console.log('error in get user data' , err.response)
      });
    }


  }
}
</script>
<style scoped>

</style>