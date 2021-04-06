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
                            <uploader v-model="formData.new_images" title="تصاویر جدید آگهی" :multiple="true" :autoUpload="false" :limit="10 - formData.images_count"></uploader>
                        </div>
                    </div>
                    <div class="row" v-show="formData.type == 1 || formData.type == 2 || formData.type == 3">
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
                            <label for="description">توضیحات تکمیلی</label>
                            <textarea rows="8" v-model="formData.description" id="description" class="form-control ">
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button @click="EditAdvertise" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ثبت</button>
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
    name:"EditAdvertise",
    data(){
        return{
            formData:{},
            errorSnackbar:false,
            errorMessage:'',
            loading:true,
            regions:{}
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
        EditAdvertise(){
            const auth_token = localStorage.getItem('auth_token');
            const editdata = new FormData();
            editdata.append('id', this.formData.id);
            editdata.append('auth_token', auth_token);
            editdata.append('title', this.formData.title);
            editdata.append('type', this.formData.type);
            editdata.append('status', this.formData.status);
            editdata.append('region_id', this.formData.region_id);
            editdata.append('address', this.formData.address);
            editdata.append('lifetime_state', this.formData.lifetime_state);
            editdata.append('skeleton_state', this.formData.skeleton_state);
            editdata.append('is_in_lane', this.formData.is_in_lane);
            editdata.append('lane_width', this.formData.lane_width);
            editdata.append('area', this.formData.area);
            editdata.append('length_house', this.formData.length_house);
            editdata.append('roof_number', this.formData.roof_number);
            editdata.append('price', this.formData.price);
            editdata.append('meed', this.formData.meed);
            editdata.append('rent', this.formData.rent);
            editdata.append('description', this.formData.description);
            editdata.append('image_delete', this.formData.image_delete);
            for(var i=0 ; i < this.formData.new_images.length ; i++){
                let file = this.formData.new_images[i].blob;
                editdata.append('new_images['+ i +']', file);
            }
            Axios.post(`advertises/edit` , editdata,
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
        getAdvertiseData(advertise_id){
            Axios.get('advertises/advertise/'+advertise_id)
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
            this.setRedirectRoute('/MyAdvertises');
            this.$router.push('/Login');
        }
        
        this.getRegions();

        if(this.$route.params.advertise_id){
            this.getAdvertiseData(this.$route.params.advertise_id)
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