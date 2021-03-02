<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item">
        <router-link :to="{name:'Admins'}"> مدیران </router-link>
      </li>
      <li class="breadcrumb-item active">پروفایل</li>
    </ol>
    <div style="height:70vh;overflow:auto;" class="container-fluid m-x-1 p-x-0 p-y-0">
      <div class="row">
        <div class="col-lg-8 col-md-12 col-xs-12 mr-auto ml-auto bg-primary text-light">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <img width="100%" :src="ImageUrl+admin.image" alt="">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h2>{{admin.name}}</h2>
            <h4>ایمیل : {{admin.email}}</h4>
            <h4>نام کاربری : {{admin.username}}</h4>
            <h4>شماره تلفن : {{admin.number}}</h4>
          </div>
        </div>
      </div>
    </div>
    
    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      
    </v-snackbar>
    <v-snackbar color="green" :timeout="4000" v-model="successSnackbar">
      <div>{{successMessage}}</div>
    </v-snackbar>

    <image-viewer v-show="preview" @closePreview="preview = false">
      <div slot="image">
        <img id="preview" :src="this.imagePreviewUrl" alt="">
      </div>
    </image-viewer>
    <div v-if="!loaded" class="arianaLoading">
      <div class="circles-wrapper">
        <div></div><div></div><div></div><div></div>
      </div>
    </div>

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import ImageViewer from '../MyComponents/ImageViewer';
export default {
  components: { ImageViewer },
  name:"AdminProfile",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      thumbnail:false,
      thumbnailedit:false,
      admin:{},
      createModal:false,
      editModal:false,
      deleteModal:false,
      editFormData:{},
      errors:{
        name:null,
        email:null,
        username:null,
        password:null,
        number:null,
      },
      hasError: false,
      errorSnackbar: false,
      successSnackbar: false,
      successMessage: '',
      loaded:false   
    }
  },
  methods:{
    showImage(Image_url){
      this.imagePreviewUrl = Image_url;
      this.preview = true;
    },
    getAdmin(){
      Axios.get('admins/profile')
      .then(res => {
        this.admin = res.data;
        this.editFormData = res.data;
        this.loaded = true;
      })
      .catch(err => {
        console.log(err)
      });
    },
    EditProfile(){
      const editdata = new FormData();
      editdata.append('id', this.editFormData.id);
      editdata.append('name', this.editFormData.name);
      editdata.append('email', this.editFormData.email);
      editdata.append('username', this.editFormData.username);
      editdata.append('unhashed_password', this.editFormData.unhashed_password);
      editdata.append('number', this.editFormData.number);
      editdata.append('image', this.editFormData.image);
      editdata.append('is_block', this.editFormData.is_block);
      editdata.append('adminRole', this.editFormData.adminRole);
      Axios.post(`admins/edit` , editdata,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.array = [];
        this.getAdmins(1);
        this.editModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    },
  },
  watch:{
    
  },
  mounted(){
    this.getAdmin();
  }
}
</script>
<style>

</style>