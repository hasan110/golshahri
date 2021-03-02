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
                        <label for="type">نوع آگهی <span class="text-danger">*</span></label>
                        <select v-model="formData.type" id="type" class="form-control ">
                        <option value="فروش">فروش</option>
                        <option value="رهن و اجاره">رهن و اجاره</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-6">
                        <label for="status">نوع ملک <span class="text-danger">*</span></label>
                        <select v-model="formData.status" id="status" class="form-control ">
                        <option value="منزل">منزل</option>
                        <option value="مغازه">مغازه</option>
                        <option value="زمین">زمین</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="neighborhood">محله <span class="text-danger">*</span></label>
                        <select v-model="formData.neighborhood" id="neighborhood" class="form-control ">
                        <option value="گلشهر">گلشهر</option>
                        <option value="نوکاریز">نوکاریز</option>
                        <option value="شهرک ثامن">شهرک ثامن</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div v-show="formData.status !== 'زمین'" class="form-group col-md-6">
                        <label for="lifetime_state">وضعیت عمر منزل <span class="text-danger">*</span></label>
                        <select v-model="formData.lifetime_state" id="lifetime_state" class="form-control ">
                        <option value="نوساز">نوساز</option>
                        <option value="معمولی">معمولی</option>
                        <option value="قدیمی ساز">قدیمی ساز</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="street">خیابان <span class="text-danger">*</span></label>
                        <input v-model="formData.street" type="text" id="street" class="form-control " placeholder="نام و شماره خیابان -> مثال : آوینی 54">
                    </div>
                    <div v-show="formData.status == 'منزل' && formData.type == 'فروش'" class="form-group col-md-6">
                        <label for="skeleton_state">وضعیت اسکلت بندی <span class="text-danger">*</span></label>
                        <select v-model="formData.skeleton_state" id="skeleton_state" class="form-control ">
                        <option value="اسکلت">اسکلت</option>
                        <option value="نیمه اسکلت">نیمه اسکلت</option>
                        <option value="بدون اسکلت">بدون اسکلت</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div v-show="formData.status !== 'مغازه'" class="form-group col-md-6">
                        <label for="lifetime_state">موقعیت منزل <span class="text-danger">*</span></label>
                        <v-radio-group v-model="formData.is_in_lane" >
                        <v-radio label="داخل خیابان" :value="0"></v-radio>
                        <v-radio label="داخل کوچه" :value="1"></v-radio>
                        </v-radio-group>
                    </div>
                    <div v-show="formData.type == 'فروش' && formData.status !== 'مغازه'" class="form-group col-md-6">
                        <label for="lane_width">عرض کوچه / خیابان (متر)</label>
                        <input v-model="formData.lane_width" min="0" type="number" id="lane_width" @input="InsertJustNumber" class="form-control " placeholder="عرض کوچه یا خیابان را وارد کنید">
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-4">
                        <label for="area">متراژ کل <span class="text-danger">*</span></label>
                        <input v-model="formData.area" min="0" type="number" @input="InsertJustNumber" id="area" class="form-control" placeholder="متراژ کل را وارد کنید">
                    </div>
                    <div v-show="formData.type == 'فروش'" class="form-group col-md-4">
                        <label for="length_house">طول حاشیه</label>
                        <input v-model="formData.length_house" min="0" type="number" @input="InsertJustNumber" id="length_house" class="form-control" placeholder="طول حاشیه را وارد کنید">
                    </div>
                    <div v-show="formData.status == 'منزل'" class="form-group col-md-4">
                        <label for="roof_number">تعداد طبقات</label>
                        <input v-model="formData.roof_number" min="0" type="number" @input="InsertJustNumber" id="roof_number" class="form-control" placeholder="تعداد طبقات را وارد کنید">
                    </div>
                    </div>
                    <div v-show="formData.type == 'فروش'" class="row">
                    <div class="form-group col-md-6">
                        <label for="price">قیمت <span class="text-danger">*</span></label>
                        <input v-model="formData.price" min="0" type="number" @input="InsertJustNumber" id="price" class="form-control" placeholder="قیمت را به میلیون تومان وارد کنید">
                    </div>
                    </div>
                    <div v-show="formData.type == 'رهن و اجاره'" class="row">
                    <div class="form-group col-md-6">
                        <label for="rent">میزان رهن <span class="text-danger">*</span></label>
                        <input v-model="formData.rent" min="0" type="number" @input="InsertJustNumber" id="rent" class="form-control" placeholder="اگر فقط اجاره می دهید اینجا صفر وارد کنید">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="meed">میزان اجاره <span class="text-danger">*</span></label>
                        <input v-model="formData.meed" min="0" type="number" @input="InsertJustNumber" id="meed" class="form-control" placeholder="اگر فقط رهن می دهید اینجا صفر وارد کنید">
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div><small class="text-primary">گذاشتن تصویر باعث بازدید بیشتر می شود.</small></div>
                            <uploader v-model="formData.images" title="تصاویر آگهی" :multiple="true" :autoUpload="false" :limit="10"></uploader>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="description">توضیحات تکمیلی</label>
                            <br>
                            <small class="text-primary">لطفا علاوه بر اشاره به اطلاعات ضروری به اطلاعات مربوط به هال , اتاق خواب , آشپزخانه و ... اشاره نمایید </small>
                            <br><br>
                            <textarea placeholder="" rows="8" v-model="formData.description" id="description" class="form-control "></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button @click="CreateAdvertise" class="btn btn-primary">
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

        <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
            <div>{{errorMessage}}</div>
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
import Uploader from "vux-uploader-component";
import { mapActions } from 'vuex'
export default {
    components: {
      Uploader
    },
    name:"CreateAdvertise",
    data(){
        return{
            formData:{
                title:'',
                type:'فروش',
                status:'منزل',
                neighborhood:'گلشهر',
                street:'',
                lifetime_state: 'نوساز',
                skeleton_state: 'اسکلت',
                is_in_lane:0,
                lane_width:'',
                area:'',
                length_house:'',
                roof_number:'',
                price:'',
                rent:'',
                meed:'',
                description:'',
                images:[],
            },
            errorSnackbar:false,
            errorMessage:'',
            loading:false,
        }
    },
    methods :{
        ...mapActions([
            'setRedirectRoute',
        ]),
        CreateAdvertise(){
            if(this.formData.title === ''){
                this.errorMessage = 'عنوان آگهی اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.street === ''){
                this.errorMessage = 'نام خیابان اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.area === ''){
                this.errorMessage = 'متراژ کل اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.type === 'فروش'){
                
                if(this.formData.price === ''){
                    this.errorMessage = 'قیمت اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                    this.errorSnackbar = true;
                    return;
                }

            }
            if(this.formData.type === 'رهن و اجاره'){
                
                if(this.formData.rent === ''){
                    this.errorMessage = 'میزان رهن اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                    this.errorSnackbar = true;
                    return;
                }
                if(this.formData.meed === ''){
                    this.errorMessage = 'میزان اجاره اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                    this.errorSnackbar = true;
                    return;
                }
            }
            this.loading = true;
            const auth_token = localStorage.getItem('auth_token');
            const data = new FormData();
            data.append('auth_token', auth_token);
            data.append('title', this.formData.title);
            data.append('type', this.formData.type);
            data.append('status', this.formData.status);
            data.append('neighborhood', this.formData.neighborhood);
            data.append('street', this.formData.street);
            data.append('lifetime_state', this.formData.lifetime_state);
            data.append('skeleton_state', this.formData.skeleton_state);
            data.append('is_in_lane', this.formData.is_in_lane);
            data.append('lane_width', this.formData.lane_width);
            data.append('area', this.formData.area);
            data.append('length_house', this.formData.length_house);
            data.append('roof_number', this.formData.roof_number);
            data.append('price', this.formData.price);
            data.append('meed', this.formData.meed);
            data.append('rent', this.formData.rent);
            data.append('description', this.formData.description);
            for(var i=0 ; i < this.formData.images.length ; i++){
                let file = this.formData.images[i].blob;
                data.append('images['+ i +']', file);
            }
            Axios.post(`advertises/create` , data,
            {
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                this.$router.push('/MyAdvertises');
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
        }
    },
    beforeMount(){
        if(localStorage.getItem('auth_token') === null){
            this.setRedirectRoute('/CreateAdvertise');
            this.$router.push('/Login');
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