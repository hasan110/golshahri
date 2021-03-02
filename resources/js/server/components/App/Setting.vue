<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">تنظیمات</li>
    </ol>
    <div style="height:80vh;overflow:auto;" class="container-fluid m-x-1 p-x-3 p-y-0">
      
      <div class="row py-2 px-2">
        <div class="form-group col-md-12 col-sm-12">
          <label for="sms_service_api_key">(api_key) سامانه پیامکی</label>
          <input v-model="formData.sms_service_api_key" type="text" class="form-control" id="sms_service_api_key">
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <label for="default_phone_number">شماره تماس پیشفرض آگهی ها</label>
          <input v-model="formData.default_phone_number" type="text" class="form-control" id="default_phone_number">
        </div>
        <div class="form-group col-md-6 col-sm-12">
          <label for="instagram_address">آدرس پیج اینستاگرام</label>
          <input v-model="formData.instagram_address" type="text" class="form-control" id="instagram_address">
        </div>
        <div class="form-group col-md-6 col-sm-12">
          <label for="telegram_address">آدرس پیج تلگرام</label>
          <input v-model="formData.telegram_address" type="text" class="form-control" id="telegram_address">
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <v-file-input
            v-model="formData.new_advertise_default_image"
            color="grey darken-3"
            counter
            label="تصویر پیشفرض آگهی ها"
            placeholder=""
            outlined
            dense
            :show-size="1000"
          >
            <template v-slot:selection="{ text }">
              <v-chip color="grey darken-3" dark label small >
                {{ text }}
              </v-chip>
            </template>
          </v-file-input>
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <img :src="ImageUrl+formData.advertise_default_image" height="100px" class="" alt="">
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <label for="about_us_text">متن صفحه درباره ما</label>
          <vue-editor id="editor" useCustomImageHandler v-model="formData.about_us_text" />
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <v-file-input
            v-model="formData.new_logo_image"
            color="grey darken-3"
            counter
            label="تصویر لوگوی وبسایت"
            placeholder=""
            outlined
            dense
            :show-size="1000"
          >
            <template v-slot:selection="{ text }">
              <v-chip color="grey darken-3" dark label small >
                {{ text }}
              </v-chip>
            </template>
          </v-file-input>
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <img :src="ImageUrl+formData.logo_image" height="100px" class="" alt="">
        </div>

         <div class="form-group col-md-12 col-sm-12">
          <v-file-input
            v-model="formData.new_instagram_image"
            color="grey darken-3"
            counter
            label="تصویر لوگوی اینستاگرام"
            placeholder=""
            outlined
            dense
            :show-size="1000"
          >
            <template v-slot:selection="{ text }">
              <v-chip color="grey darken-3" dark label small >
                {{ text }}
              </v-chip>
            </template>
          </v-file-input>
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <img :src="ImageUrl+formData.instagram_image" height="100px" class="" alt="">
        </div>

        
         <div class="form-group col-md-12 col-sm-12">
          <v-file-input
            v-model="formData.new_telegram_image"
            color="grey darken-3"
            counter
            label="تصویر لوگوی تلگرام"
            placeholder=""
            outlined
            dense
            :show-size="1000"
          >
            <template v-slot:selection="{ text }">
              <v-chip color="grey darken-3" dark label small >
                {{ text }}
              </v-chip>
            </template>
          </v-file-input>
        </div>
        <div class="form-group col-md-12 col-sm-12">
          <img :src="ImageUrl+formData.telegram_image" height="100px" class="" alt="">
        </div>


        <button @click="editSetting" class="btn btn-primary">ثبت اطلاعات</button>
      </div>
    </div>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div>{{errors.title}}</div>
      <div>{{errors.place}}</div>
      <div>{{errors.release}}</div>
      <div>{{errors.expiry}}</div>
    </v-snackbar>
    <v-snackbar color="green" :timeout="4000" v-model="successSnackbar">
      <div>{{successMessage}}</div>
    </v-snackbar>

    <image-viewer v-show="preview" @closePreview="preview = false">
      <div slot="image">
        <img id="preview" :src="this.imagePreviewUrl" alt="">
      </div>
    </image-viewer>

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import ImageViewer from '../MyComponents/ImageViewer';
export default {
  components: { ImageViewer },
  name:"Setting",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      loaded:false,
      formData:{},
      errors:{},
      hasError: false,
      errorSnackbar: false,
      successSnackbar: false,
      successMessage: ''
    }
  },
  methods:{
    showImage(Image_url){
      this.imagePreviewUrl = Image_url;
      this.preview = true;
    },
    selectSlideShowImage(event) {
      this.formData.image = this.$refs.image.files[0];
      const file = event.target.files[0];
      this.thumbnail = URL.createObjectURL(file);
    },
    editSlideShowImage(event) {
      this.editFormData.image = this.$refs.editimage.files[0];
      const file = event.target.files[0];
      this.thumbnailedit = URL.createObjectURL(file);
    },
    getSetting(){
      Axios.get(`settings/setting`)
      .then(res => {
          this.formData = res.data;
          this.loaded = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    editSetting(){
      const data = new FormData();
      data.append('default_phone_number', this.formData.default_phone_number);
      data.append('instagram_address', this.formData.instagram_address);
      data.append('telegram_address', this.formData.telegram_address);
      data.append('sms_service_api_key', this.formData.sms_service_api_key);
      data.append('about_us_text', this.formData.about_us_text);
      data.append('new_advertise_default_image', this.formData.new_advertise_default_image);
      data.append('new_logo_image', this.formData.new_logo_image);
      data.append('new_telegram_image', this.formData.new_telegram_image);
      data.append('new_instagram_image', this.formData.new_instagram_image);
      Axios.post(`settings/edit` , data,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.getSetting();
        // this.$refs.image.value=null;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    }
  },
  mounted(){
    this.getSetting();
  }
}
</script>
<style>
.tag{
  cursor: pointer;
}
</style>