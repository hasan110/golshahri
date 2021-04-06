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
                            <input v-model="formData.title" type="text" id="title" class="form-control " placeholder="عنوان مناسبی برای آگهی خود انتخاب کنید .">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="type">انتخاب نوع آگهی <span class="text-danger">*</span></label>
                            <select v-model="formData.type" id="type" class="form-control ">
                                <option value="1">فروش</option>
                                <option value="2">رهن کامل</option>
                                <option value="3">رهن و اجاره</option>
                                <option value="4">درخواست خرید</option>
                                <option value="5">درخواست رهن و اجاره</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="status">نوع ملک خود را انتخاب کنید <span class="text-danger">*</span></label>
                            <select v-model="formData.status" id="status" class="form-control ">
                            <option value="منزل">منزل</option>
                            <option value="مغازه">مغازه</option>
                            <option value="زمین">زمین</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="region_id">ملک شما در کدام منطقه است؟ <span class="text-danger">*</span></label>
                            <select v-model="formData.region_id" id="region_id" class="form-control">
                                <option v-for="(region , key) in regions" :key="key" :value="region.id">{{region.title}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div v-show="formData.type !== 4 && formData.type !== 5" class="form-group col-md-6">
                            <label for="address">آدرس دقیق <span class="text-danger">*</span></label>
                            <input v-model="formData.address" type="text" id="address" class="form-control " placeholder="لطفا آدرس دقیق ملک خود را وارد نمایید">
                        </div>
                        <div v-show="formData.type == 1 || formData.type == 4" class="form-group col-md-6">
                            <label for="price">قیمت <span class="text-danger">*</span></label>
                            <input v-model="formData.price" min="0" type="number" @input="InsertJustNumber" id="price" class="form-control" placeholder="قیمت را به میلیون تومان وارد کنید">
                        </div>
                        <div v-show="formData.status !== 'زمین'" class="form-group col-md-6">
                            <label for="lifetime_state">وضعیت عُمر <span class="text-danger">*</span></label>
                            <select v-model="formData.lifetime_state" id="lifetime_state" class="form-control ">
                            <option value="نوساز">نوساز</option>
                            <option value="معمولی">معمولی</option>
                            <option value="قدیمی ساز">قدیمی ساز</option>
                            </select>
                        </div>
                        <div v-show="formData.status == 'منزل' && formData.type == 1 || formData.type == 4" class="form-group col-md-6">
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
                            <label for="lifetime_state">موقعیت ملک <span class="text-danger">*</span></label>
                            <v-radio-group v-model="formData.is_in_lane" >
                            <v-radio label="داخل خیابان" :value="0"></v-radio>
                            <v-radio label="داخل کوچه" :value="1"></v-radio>
                            </v-radio-group>
                        </div>
                        <div v-show="formData.type == 1 && formData.status !== 'مغازه'" class="form-group col-md-6">
                            <label for="lane_width">عرض کوچه / خیابان (متر)</label>
                            <input v-model="formData.lane_width" min="0" type="number" id="lane_width" @input="InsertJustNumber" class="form-control " placeholder="عرض کوچه یا خیابان را وارد کنید">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="area">متراژ کل <span class="text-danger">*</span></label>
                            <input v-model="formData.area" min="0" type="number" @input="InsertJustNumber" id="area" class="form-control" placeholder="متراژ کل را وارد کنید">
                        </div>
                        <div v-show="formData.type == 1" class="form-group col-md-4">
                            <label for="length_house">طول حاشیه</label>
                            <input v-model="formData.length_house" min="0" type="number" @input="InsertJustNumber" id="length_house" class="form-control" placeholder="طول حاشیه را وارد کنید">
                        </div>
                        <div v-show="formData.status == 'منزل'" class="form-group col-md-4">
                            <label for="roof_number">تعداد طبقات</label>
                            <input v-model="formData.roof_number" min="0" type="number" @input="InsertJustNumber" id="roof_number" class="form-control" placeholder="تعداد طبقات را وارد کنید">
                        </div>
                    </div>
                    <div class="row">
                        <div v-show="formData.type == 2 || formData.type == 3 || formData.type == 5" class="form-group col-md-6">
                            <label for="rent">میزان رهن <span class="text-danger">*</span></label>
                            <input v-model="formData.rent" min="0" type="number" @input="InsertJustNumber" id="rent" class="form-control" placeholder="اگر فقط اجاره می دهید اینجا صفر وارد کنید">
                        </div>
                        <div v-show="formData.type == 3 || formData.type == 5" class="form-group col-md-6">
                            <label for="meed">میزان اجاره <span class="text-danger">*</span></label>
                            <input v-model="formData.meed" min="0" type="number" @input="InsertJustNumber" id="meed" class="form-control" placeholder="اگر فقط رهن می دهید اینجا صفر وارد کنید">
                        </div>
                    </div>
                    <div class="row" v-show="formData.type == 1 || formData.type == 2 || formData.type == 3">
                        <div class="col-12">
                            <div><small class="text-primary">تصاویر مسکن خود را از این قسمت انتخاب کنید.</small></div>
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
    name:"CreateAdvertise",
    data(){
        return{
            regions:{},
            formData:{
                title:'',
                type:1,
                status:'منزل',
                region_id:1,
                address:'',
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
        getRegions(){
            Axios.get('advertises/regions')
            .then(res => {
                this.regions = res.data;
            })
            .catch(err => {
                console.log(err)
            });
        },
        CreateAdvertise(){
            if(this.formData.title === ''){
                this.errorMessage = 'عنوان آگهی اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.address === ''){
                this.errorMessage = 'آدرس دقیق اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.area === ''){
                this.errorMessage = 'متراژ کل اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                this.errorSnackbar = true;
                return;
            }
            if(this.formData.type === 1){
                
                if(this.formData.price === ''){
                    this.errorMessage = 'قیمت اجباری است (لطفا فیلد های ستاره دار را تکمیل نمایید)';
                    this.errorSnackbar = true;
                    return;
                }

            }
            if(this.formData.type === 2){
                
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
            data.append('region_id', this.formData.region_id);
            data.append('address', this.formData.address);
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
        this.getRegions();
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
    .vux-uploader_input-box {
        border: 2px solid #0a3087 !important;
        border-radius: 12px;
    }
    .vux-uploader_input-box:before {
        background-color: #0a3087 !important;
    }
    .vux-uploader_input-box:after {
        background-color: #0a3087 !important;
    }
</style>