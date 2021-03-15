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
                        <label for="type">نوع آگهی *</label>
                        <select v-model="formData.type" id="type" class="form-control ">
                        <option value="فروش">فروش</option>
                        <option value="رهن و اجاره">رهن و اجاره</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-6">
                        <label for="status">نوع ملک *</label>
                        <select v-model="formData.status" id="status" class="form-control ">
                        <option value="منزل">منزل</option>
                        <option value="مغازه">مغازه</option>
                        <option value="زمین">زمین</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="region_id">انتخاب منطقه <span class="text-danger">*</span></label>
                        <select v-model="formData.region_id" id="region_id" class="form-control">
                            <option v-for="(region , key) in regions" :key="key" :value="region.id">{{region.title}}</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div v-show="formData.status !== 'زمین'" class="form-group col-md-6">
                        <label for="lifetime_state">وضعیت عمر منزل *</label>
                        <select v-model="formData.lifetime_state" id="lifetime_state" class="form-control">
                        <option value="نوساز">نوساز</option>
                        <option value="معمولی">معمولی</option>
                        <option value="قدیمی ساز">قدیمی ساز</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="street">خیابان *</label>
                        <input v-model="formData.street" type="text" id="street" class="form-control" placeholder="نام خیابان را وارد کنید">
                    </div>
                    <div v-show="formData.status == 'منزل' && formData.type == 'فروش'" class="form-group col-md-6">
                        <label for="skeleton_state">وضعیت اسکلت بندی *</label>
                        <select v-model="formData.skeleton_state" id="skeleton_state" class="form-control">
                        <option value="اسکلت">اسکلت</option>
                        <option value="نیمه اسکلت">نیمه اسکلت</option>
                        <option value="بدون اسکلت">بدون اسکلت</option>
                        </select>
                    </div>
                    </div>
                    <div class="row">
                    <div v-show="formData.status !== 'مغازه'" class="form-group col-md-6">
                        <label for="lifetime_state">موقعیت منزل *</label>
                        <v-radio-group v-model="formData.is_in_lane" >
                        <v-radio label="داخل خیابان" :value="0"></v-radio>
                        <v-radio label="داخل کوچه" :value="1"></v-radio>
                        </v-radio-group>
                    </div>
                    <div v-show="formData.type == 'فروش' || formData.status !== 'مغازه'" class="form-group col-md-6">
                        <label for="lane_width">عرض کوچه / خیابان</label>
                        <input v-model="formData.lane_width" type="number" @input="InsertJustNumber" min="0" id="lane_width" class="form-control " placeholder="متراژ عرض کوچه / خیابان را وارد کنید">
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-4">
                        <label for="area">متراژ کل *</label>
                        <input v-model="formData.area" type="number" @input="InsertJustNumber" min="0" id="area" class="form-control" placeholder="متراژ کل را وارد کنید">
                    </div>
                    <div v-show="formData.type == 'فروش'" class="form-group col-md-4">
                        <label for="length_house">طول حاشیه</label>
                        <input v-model="formData.length_house" type="number" @input="InsertJustNumber" min="0" id="length_house" class="form-control" placeholder="طول حاشیه را وارد کنید">
                    </div>
                    <div v-show="formData.status == 'منزل'" class="form-group col-md-4">
                        <label for="roof_number">تعداد طبقات</label>
                        <input v-model="formData.roof_number" type="number" @input="InsertJustNumber" min="0" id="roof_number" class="form-control" placeholder="تعداد طبقات را وارد کنید">
                    </div>
                    </div>
                    <div v-show="formData.type == 'فروش'" class="row">
                    <div class="form-group col-md-6">
                        <label for="price">قیمت <span class="text-danger">*</span></label>
                        <input v-model="formData.price" type="number" @input="InsertJustNumber" min="0" id="price" class="form-control" placeholder="قیمت را وارد کنید">
                    </div>
                    </div>
                    <div v-show="formData.type == 'رهن و اجاره'" class="row">
                    <div class="form-group col-md-6">
                        <label for="rent">میزان اجاره *</label>
                        <input v-model="formData.rent" type="number" @input="InsertJustNumber" min="0" id="rent" class="form-control" placeholder="میزان اجاره را وارد کنید">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="meed">میزان کرایه</label>
                        <input v-model="formData.meed" type="number" @input="InsertJustNumber" min="0" id="meed" class="form-control" placeholder="میزان کرایه را وارد کنید">
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
            editdata.append('street', this.formData.street);
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
</style>