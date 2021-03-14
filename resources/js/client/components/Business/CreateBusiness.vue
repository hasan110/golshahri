<template>
    <div class="">
        <div class="container" style="margin-top:60px">
            <div style="height:auto;" class="card m-b-0">
                <div class="card-header">
                    ایجاد <strong>آگهی</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title">عنوان آگهی <span class="text-danger">*</span></label>
                            <input v-model="formData.title" type="text" id="title" class="form-control " placeholder="یک عنوان انتخاب کنید .">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_number"> شماره تماس </label>
                            <input v-model="formData.contact_number" @input="InsertJustNumber" type="number" id="contact_number" class="form-control " placeholder="">
                        </div>
                    </div>
                    <div class="row">
                        <small class="text-primary"> درصورت وجود،آیدی کسب و کار خود در شبکه های اجتماعی را وارد کنید</small>
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
                        <div class="form-group col-md-6">
                            <label for="category_id">دسته بندی <span class="text-danger">*</span></label>
                            <select v-model="formData.category_id" id="category_id" class="form-control">
                            <option value="">دسته بندی آگهی خود را انتخاب کنید</option>
                            <option v-for="(category , key) in categories" :key="key" :value="category.id">{{category.title}}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div><small class="text-primary">گذاشتن تصویر باعث بازدید بیشتر می شود.</small></div>
                            <uploader v-model="formData.images" title="تصاویر آگهی *" :multiple="true" :autoUpload="false" :limit="10"></uploader>
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
                    <button @click="CreateBusiness" class="btn btn-primary">
                        ثبت 
                        <i class="fa fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
        <div v-if="loading" class="send-data-loading-wrapper">
            <div class="send-data-loading-inner">
            <div class="send-data-loading">
                در حال ارسال اطلاعات
                
                <div class="spin-wrapper">
                    <div class="spin large">
                        <span class="spinner dark"></span>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <v-dialog v-model="errorSnackbar" max-width="290">
            <v-card dark color="error">
                <v-card-title class="headline">
                </v-card-title>
                <v-card-text align="center">
                    <h3><i class="fa fa-info-circle"></i></h3>
                    {{errorMessage}}<br><br>
                    <v-btn color="primary" @click="errorSnackbar = false">
                        فهمیدم !
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-dialog> 
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
    name:"CreateBusiness",
    data(){
        return{
            formData:{
                title:'',
                category_id:'',
                contact_number:'',
                instagram_id:'',
                telegram_id:'',
                description:'',
                images:[],
            },
            categories:{},
            errorSnackbar:false,
            errorMessage:'',
            loading:false,
        }
    },
    methods :{
        ...mapActions([
            'setRedirectRoute',
        ]),
        CreateBusiness(){
            if(this.formData.title === ''){
                this.errorMessage = 'عنوان آگهی اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.category_id === ''){
                this.errorMessage = 'انتخاب دسته بندی اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.images.length == 0){
                this.errorMessage = 'تصویر آگهی اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.description === ''){
                this.errorMessage = 'توضیحات آگهی اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            const auth_token = localStorage.getItem('auth_token');
            const data = new FormData();
            data.append('auth_token', auth_token);
            data.append('category_id', this.formData.category_id);
            data.append('title', this.formData.title);
            data.append('contact_number', this.formData.contact_number);
            data.append('instagram_id', this.formData.instagram_id);
            data.append('telegram_id', this.formData.telegram_id);
            data.append('description', this.formData.description);
            for(var i=0 ; i < this.formData.images.length ; i++){
                let file = this.formData.images[i].blob;
                data.append('images['+ i +']', file);
            }

            this.loading = true;
            Axios.post(`businesses/create` , data,
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
        InsertJustNumber(e){
            const isNumeric = /^[-+]?(\d+|\d+\.\d*|\d*\.\d+)$/;
            const value = e.target.value;
            if(!isNumeric.test(value)){
                e.target.value = ''
            }
        },
        getCategories(){
            Axios.get('businesses/categories')
            .then(res => {
                this.categories = res.data;
            })
            .catch(err => {
                console.log(err)
            });
        }
    },
    beforeMount(){
        if(localStorage.getItem('auth_token') === null){
            this.setRedirectRoute('/CreateBusiness');
            this.$router.push('/Login');
        }
        this.getCategories();
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