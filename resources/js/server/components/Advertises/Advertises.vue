<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">آگهی ها</li>
      <input style="max-width:150px;float:left;margin:-5px;font-size: 15px;padding: 0 5px; " placeholder="جست و جو" v-model="search_key" @input="searchAdvertise" type="text" class="form-control form-control-sm">
    </ol>
    <div class="container-fluid m-x-1 p-x-0 p-y-0">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header p-x-0 p-y-0">
              <div class="col-lg-6 col-md-6 col-xs-12 p-x-0 p-y-0">
                <button @click="createModal = true" class="btn btn-primary m-y-1 m-l-1">
                  <i class="fa fa-plus"></i>
                </button>
                <button @click="getAdvertiseData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
                  <i class="fa fa-edit"></i>
                </button>
                <button @click="deleteModal = true" v-if="array.length !== 0" class="btn btn-danger m-y-1 m-l-1">
                  <i class="fa fa-trash"></i>
                </button>
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12 p-x-0 p-y-0">
                <v-pagination
                  v-model="current_page"
                  :length="last_page"
                  :total-visible="7"
                ></v-pagination>
              </div>
            </div>
            <div style="height:70vh;overflow:auto;" class="card-block p-a-0">
              <div class="table-responsive">
                <table style="width:100%" class="table table-customize table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th></th>
                      <th>ردیف</th>
                      <th>شناسه</th>
                      <th>عنوان</th>
                      <th>آدرس</th>
                      <th>ثبت کننده</th>
                      <th>بازدید</th>
                      <th>وضعیت</th>
                      <th>تاریخ ثبت</th>
                      <th>تاریخ ویرایش</th>
                      <th>تصویر</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(advertise, key) in advertises" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="advertise.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ advertise.id }}</td>
                      <td>{{ advertise.title }}</td>
                      <td>{{ advertise.neighborhood }} - {{ advertise.street }}</td>
                      <td>
                        <template v-if="advertise.user">
                          کاربر - {{ advertise.user.name }}
                        </template>
                        <template v-else-if="advertise.admin">
                          مدیر - {{ advertise.admin.name }}
                        </template>
                        <template v-else>نا مشخص</template>
                      </td>
                      <td>{{ advertise.view_count }}</td>
                      <td>
                        <span @click="changeAdvertiseStatus(advertise.id)" v-if="advertise.confirmed" class="tag tag-success">فعال</span>
                        <span @click="changeAdvertiseStatus(advertise.id)" v-else class="tag tag-info">غیر فعال</span>
                      </td>
                      <td>{{ advertise.shamsi_created_at }}</td>
                      <td>{{ advertise.shamsi_updated_at }}</td>
                      <td>
                        <template v-if="advertise.images.length">
                          <img class="icon" :src="ImageUrl+advertise.images[0].link" alt="">
                        </template>
                        <template v-else>ندارد</template>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modals for operations -->
    <v-dialog v-model="createModal" scrollable max-width="900px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ایجاد <strong>آگهی</strong>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="title">عنوان آگهی *</label>
                <input v-model="formData.title" type="text" id="title" class="form-control" placeholder="عنوان آگهی را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="type">نوع آگهی *</label>
                <select v-model="formData.type" id="type" class="form-control">
                  <option value="فروش">فروش</option>
                  <option value="رهن و اجاره">رهن و اجاره</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="status">نوع ملک *</label>
                <select v-model="formData.status" id="status" class="form-control">
                  <option value="منزل">منزل</option>
                  <option value="مغازه">مغازه</option>
                  <option value="زمین">زمین</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="neighborhood">محله *</label>
                <select v-model="formData.neighborhood" id="neighborhood" class="form-control">
                  <option value="گلشهر">گلشهر</option>
                  <option value="نوکاریز">نوکاریز</option>
                  <option value="شهرک ثامن">شهرک ثامن</option>
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
              <div v-show="formData.type == 'فروش' && formData.status !== 'مغازه'" class="form-group col-md-6">
                <label for="lane_width">عرض کوچه / خیابان</label>
                <input v-model="formData.lane_width" type="number" @input="InsertJustNumber" min="0" id="lane_width" class="form-control" placeholder="متراژ عرض کوچه / خیابان را وارد کنید">
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
                <input v-model="formData.price" type="number" id="price" @input="InsertJustNumber" min="0" class="form-control" placeholder="قیمت را وارد کنید">
              </div>
            </div>
            <div v-show="formData.type == 'رهن و اجاره'" class="row">
              <div class="form-group col-md-6">
                <label for="rent">میزان رهن *</label>
                <input v-model="formData.rent" type="number" @input="InsertJustNumber" min="0" id="rent" class="form-control" placeholder="میزان رهن را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="meed">میزان اجاره *</label>
                <input v-model="formData.meed" type="number" @input="InsertJustNumber" min="0" id="meed" class="form-control" placeholder="میزان اجاره را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <v-file-input
                  v-model="formData.images"
                  color="grey darken-3"
                  counter
                  label="انتخاب تصاویر"
                  multiple
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
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="description">توضیحات تکمیلی</label>
                <textarea rows="6" v-model="formData.description" id="description" class="form-control">
                </textarea>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="address">آدرس دقیق</label>
                <textarea rows="2" v-model="formData.address" id="address" class="form-control" placeholder="این آدرس فقط برای مدیر قابل رویت است">
                </textarea>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="note">یادداشت مدیر</label>
                <textarea rows="2" v-model="formData.note" id="note" class="form-control" placeholder="این یادداشت فقط برای مدیر قابل رویت است">
                </textarea>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreateAdvertise" class="btn btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="900px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ویرایش <strong>آگهی</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="title">عنوان آگهی *</label>
                <input v-model="editFormData.title" type="text" id="title" class="form-control" placeholder="عنوان آگهی را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="type">نوع آگهی *</label>
                <select v-model="editFormData.type" id="type" class="form-control">
                  <option value="فروش">فروش</option>
                  <option value="رهن و اجاره">رهن و اجاره</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="status">نوع ملک *</label>
                <select v-model="editFormData.status" id="status" class="form-control">
                  <option value="منزل">منزل</option>
                  <option value="مغازه">مغازه</option>
                  <option value="زمین">زمین</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="neighborhood">محله *</label>
                <select v-model="editFormData.neighborhood" id="neighborhood" class="form-control">
                  <option value="گلشهر">گلشهر</option>
                  <option value="نوکاریز">نوکاریز</option>
                  <option value="شهرک ثامن">شهرک ثامن</option>
                </select>
              </div>
            </div>
            
            <div class="row">
              <div v-show="editFormData.status !== 'زمین'" class="form-group col-md-6">
                <label for="lifetime_state">وضعیت عمر منزل *</label>
                <select v-model="editFormData.lifetime_state" id="lifetime_state" class="form-control">
                  <option value="نوساز">نوساز</option>
                  <option value="معمولی">معمولی</option>
                  <option value="قدیمی ساز">قدیمی ساز</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="street">خیابان *</label>
                <input v-model="editFormData.street" type="text" id="street" class="form-control" placeholder="نام خیابان را وارد کنید">
              </div>
              <div v-show="editFormData.status == 'منزل' && editFormData.type == 'فروش'" class="form-group col-md-6">
                <label for="skeleton_state">وضعیت اسکلت بندی *</label>
                <select v-model="editFormData.skeleton_state" id="skeleton_state" class="form-control">
                  <option value="اسکلت">اسکلت</option>
                  <option value="نیمه اسکلت">نیمه اسکلت</option>
                  <option value="بدون اسکلت">بدون اسکلت</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div v-show="editFormData.status !== 'مغازه'" class="form-group col-md-6">
                <label for="lifetime_state">موقعیت منزل *</label>
                <v-radio-group v-model="editFormData.is_in_lane" >
                  <v-radio label="داخل خیابان" :value="0"></v-radio>
                  <v-radio label="داخل کوچه" :value="1"></v-radio>
                </v-radio-group>
              </div>
              <div v-show="editFormData.type == 'فروش' || editFormData.status !== 'مغازه'" class="form-group col-md-6">
                <label for="lane_width">عرض کوچه / خیابان</label>
                <input v-model="editFormData.lane_width" type="number" @input="InsertJustNumber" min="0" id="lane_width" class="form-control" placeholder="متراژ عرض کوچه / خیابان را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="area">متراژ کل *</label>
                <input v-model="editFormData.area" type="number" @input="InsertJustNumber" min="0" id="area" class="form-control" placeholder="متراژ کل را وارد کنید">
              </div>
              <div v-show="editFormData.type == 'فروش'" class="form-group col-md-4">
                <label for="length_house">طول حاشیه</label>
                <input v-model="editFormData.length_house" type="number" @input="InsertJustNumber" min="0" id="length_house" class="form-control" placeholder="طول حاشیه را وارد کنید">
              </div>
              <div v-show="editFormData.status == 'منزل'" class="form-group col-md-4">
                <label for="roof_number">تعداد طبقات</label>
                <input v-model="editFormData.roof_number" type="number" @input="InsertJustNumber" min="0" id="roof_number" class="form-control" placeholder="تعداد طبقات را وارد کنید">
              </div>
            </div>
            <div v-show="editFormData.type == 'فروش'" class="row">
              <div class="form-group col-md-6">
                <label for="price">قیمت <span class="text-danger">*</span></label>
                <input v-model="editFormData.price" type="number" @input="InsertJustNumber" min="0" id="price" class="form-control" placeholder="قیمت را وارد کنید">
              </div>
            </div>
            <div v-show="editFormData.type == 'رهن و اجاره'" class="row">
              <div class="form-group col-md-6">
                <label for="rent">میزان رهن *</label>
                <input v-model="editFormData.rent" type="number" @input="InsertJustNumber" min="0" id="rent" class="form-control" placeholder="میزان رهن را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="meed">میزان اجاره *</label>
                <input v-model="editFormData.meed" type="number" @input="InsertJustNumber" min="0" id="meed" class="form-control" placeholder="میزان اجاره را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <v-file-input
                  v-model="editFormData.new_images"
                  color="grey darken-3"
                  counter
                  label="انتخاب تصاویر"
                  multiple
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
            </div>
            <div class="row">
              <div v-for="(image,key) in editFormData.images" :key="key" class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                <div class="card">
                  <img class="card-img-top img-fluid" :src="ImageUrl+image.link" alt="image">
                  <div class="card-footer">
                    <small class="text-muted">حذف شود؟</small>
                    <v-checkbox v-model="editFormData.image_delete" color="blue" :value="image.id"></v-checkbox>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="description">توضیحات تکمیلی</label>
                <textarea rows="6" v-model="editFormData.description" id="description" class="form-control">
                </textarea>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="address">آدرس دقیق</label>
                <textarea rows="2" v-model="editFormData.address" id="address" class="form-control" placeholder="این آدرس فقط برای مدیر قابل رویت است">
                </textarea>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="note"> یادداشت مدیر</label>
                <textarea rows="2" v-model="editFormData.note" id="note" class="form-control" placeholder="این یادداشت فقط برای مدیر قابل رویت است">
                </textarea>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="EditAdvertise" class="btn btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
            حذف آگهی
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deleteAdvertise" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
            <button  @click="deleteModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div>{{errorMessage}}</div>
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
  name:"Advertises",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      array:[],
      advertises:{},
      loaded:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
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
        address:'',
        note:'',
        images:null,
      },
      editFormData:{},
      errorSnackbar: false,
      errorMessage: '',
      successSnackbar: false,
      successMessage: '',     
      search_key: ''
    }
  },
  methods:{
    showImage(Image_url){
      this.imagePreviewUrl = Image_url;
      this.preview = true;
    },
    selectAdvertiseImages(event) {
      this.formData.images = this.$refs.images.files[0];
    },
    editAdvertiseImage(event) {
      this.editFormData.images = this.$refs.editimages.files[0];
    },
    getAdvertises(page){
      Axios.get(`advertises/list?page=${page}`)
      .then(res => {
        this.advertises = res.data.data;
        this.last_page = res.data.last_page;
        this.loaded = true;
        this.array = [];
      })
      .catch(err => {
          console.log(err)
      });
    },
    CreateAdvertise(){
      const data = new FormData();
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
      data.append('address', this.formData.address);
      data.append('note', this.formData.note);
      if(this.formData.images !== null){
        for(var i=0 ; i < this.formData.images.length ; i++){
          let file = this.formData.images[i];
          data.append('images['+ i +']', file);
        }
      }
      Axios.post(`advertises/create` , data,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.getAdvertises(1);
        this.createModal = false;
        this.formData = {
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
          address:'',
          note:'',
          images:null,
        };
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        // this.$refs.images.value=null;
      })
      .catch(err => {
        if(err.response.data.status == 'failed'){
          this.errorMessage = err.response.data.message;
          this.errorSnackbar = true;
        }
      });
    },
    getAdvertiseData(){
      Axios.get('advertises/advertise/'+this.array[0])
      .then(res => {
        this.editFormData = res.data;
        this.editModal = true;
        this.thumbnailedit = false;
      })
      .catch(err => {
          console.log(err)
      });
    },
    EditAdvertise(){
      const editdata = new FormData();
      editdata.append('id', this.editFormData.id);
      editdata.append('title', this.editFormData.title);
      editdata.append('type', this.editFormData.type);
      editdata.append('status', this.editFormData.status);
      editdata.append('neighborhood', this.editFormData.neighborhood);
      editdata.append('street', this.editFormData.street);
      editdata.append('lifetime_state', this.editFormData.lifetime_state);
      editdata.append('skeleton_state', this.editFormData.skeleton_state);
      editdata.append('is_in_lane', this.editFormData.is_in_lane);
      editdata.append('lane_width', this.editFormData.lane_width);
      editdata.append('area', this.editFormData.area);
      editdata.append('length_house', this.editFormData.length_house);
      editdata.append('roof_number', this.editFormData.roof_number);
      editdata.append('price', this.editFormData.price);
      editdata.append('meed', this.editFormData.meed);
      editdata.append('rent', this.editFormData.rent);
      editdata.append('description', this.editFormData.description);
      editdata.append('address', this.editFormData.address);
      editdata.append('note', this.editFormData.note);
      editdata.append('image_delete', this.editFormData.image_delete);
      for(var i=0 ; i < this.editFormData.new_images.length ; i++){
        let file = this.editFormData.new_images[i];
        editdata.append('new_images['+ i +']', file);
      }
      Axios.post(`advertises/edit` , editdata,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.array = [];
        this.getAdvertises(1);
        this.editModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.editFormData = {};
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    },
    deleteAdvertise(){
      Axios.post('advertises/delete' , {array:this.array})
      .then(res => {
        this.array = [];
        this.getAdvertises(1);
        this.deleteModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    changeAdvertiseStatus(advertise_id){
      Axios.post('advertises/changeStatus' , {advertise_id})
      .then(res => {
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.getAdvertises(this.current_page);
      })
      .catch(err => {
          console.log(err)
      });
    },
    searchAdvertise(){
      this.loaded = false;
      const key = this.search_key
      Axios.post('advertises/search' , {key})
      .then(res => {
        this.advertises = res.data.data;
        this.last_page = res.data.last_page;
        this.loaded = true;
        this.array = [];
      })
      .catch(err => {
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
  watch:{
    current_page: function (page) {
      this.getAdvertises(page);
    }
  },
  mounted(){
    this.getAdvertises(this.current_page);
  }
}
</script>
<style>

</style>