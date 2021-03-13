<template>
    <div class="">
        <div class="container" style="margin-top:50px">
            <div style="height:auto;" class="card m-b-0">
                <div class="card-header">
                    ویرایش <strong>آگهی</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title">عنوان آگهی *</label>
                            <input v-model="formData.title" type="text" id="title" class="form-control " placeholder="عنوان آگهی را وارد کنید">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_number"> شماره تماس </label>
                            <input v-model="formData.contact_number" @input="InsertJustNumber" type="text" id="contact_number" class="form-control " placeholder="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="telegram_id">آیدی تلگرامی: </label>
                            <input v-model="formData.telegram_id" type="text" id="telegram_id" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="instagram_id">آیدی اینستاگرامی:</label>
                            <input v-model="formData.instagram_id" type="text" id="instagram_id" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <uploader v-model="formData.new_images" title="تصاویر جدید آگهی" :multiple="true" :autoUpload="false" :limit="10 - formData.images_count"></uploader>
                        </div>
                    </div>
                    <div class="row">
                        <div v-for="(image,key) in formData.images" :key="key" class="adv-img-wrp">
                            <img class="adv-img-inr" :src="ImageUrl+image.link" alt="image">
                            <div class="adv-img-del">
                                <small>حذف ؟</small>
                                <v-checkbox v-model="formData.image_delete" color="blue" :value="image.id"></v-checkbox>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="description">توضیحات تکمیلی <span class="text-danger">*</span>
                            </label>
                            <br>
                            <small class="text-primary">در خصوص محصول یا خدمت خود توضیحات لازم را ارائه کنید</small>
                            <br><br>
                            <textarea placeholder="" rows="8" v-model="formData.description" id="description" class="form-control "></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button @click="EditBusiness" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ویرایش</button>
                </div>
            </div>
        </div>

        <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
            <div>{{errorMessage}}</div>
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
import Uploader from "vux-uploader-component";
import { mapActions } from 'vuex'
export default {
    components: {
      Uploader
    },
    name:"EditBusiness",
    data(){
        return{
            formData:{},
            errorSnackbar:false,
            errorMessage:'',
            loading:true,
        }
    },
    methods :{
        ...mapActions([
            'setRedirectRoute',
        ]),
        EditBusiness(){
            const auth_token = localStorage.getItem('auth_token');
            const editdata = new FormData();
            editdata.append('id', this.formData.id);
            editdata.append('auth_token', auth_token);
            editdata.append('title', this.formData.title);
            editdata.append('contact_number', this.formData.contact_number);
            editdata.append('instagram_id', this.formData.instagram_id);
            editdata.append('telegram_id', this.formData.telegram_id);
            editdata.append('description', this.formData.description);
            editdata.append('image_delete', this.formData.image_delete);
            for(var i=0 ; i < this.formData.new_images.length ; i++){
                let file = this.formData.new_images[i].blob;
                editdata.append('new_images['+ i +']', file);
            }
            Axios.post(`businesses/edit` , editdata,
            {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                this.$router.push('/MyBusinesses');
            })
            .catch(err => {
                this.loading = false;
                this.errorMessage = err.response.data.message;
                this.errorSnackbar = true;
            });
        },
        getBusinessData(business_id){
            Axios.get('businesses/business/'+business_id)
            .then(res => {
                this.formData = res.data;
                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                console.log(err)
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
        if(localStorage.getItem('auth_token') === null){
            this.setRedirectRoute('/MyBusinesses');
            this.$router.push('/Login');
        }

        if(this.$route.params.business_id){
            this.getBusinessData(this.$route.params.business_id)
        }else{
            this.$router.push({ name: 'NotFound'});
        }
    }
}
</script>
<style>
.vux-uploader .vux-uploader_bd .vux-uploader_input-box{
    float: right !important;
}
.vux-uploader .vux-uploader_bd{
    overflow: inherit !important;
}
.vux-uploader .vux-uploader_bd .vux-uploader_files .vux-uploader_file {
    float: right !important;
}
</style>