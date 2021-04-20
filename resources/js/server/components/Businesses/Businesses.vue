<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">کسب و کارها</li>
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
                <button @click="getBusinessData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                      <th>ثبت کننده</th>
                      <th>بازدید</th>
                      <th>وضعیت</th>
                      <th>تاریخ ثبت</th>
                      <th>تصویر</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(business, key) in businesses" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="business.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ business.id }}</td>
                      <td>{{ business.title }}</td>
                      <td>
                        <template v-if="business.user">
                          {{ business.user.name }}
                        </template>
                        <template v-else><i class="fa fa-user"></i> ادمین</template>
                      </td>
                      <td>{{ business.view_count }}</td>
                      <td>
                        <span @click="changeBusinessStatus(business.id)" v-if="business.confirmed" class="tag tag-success">فعال</span>
                        <span @click="changeBusinessStatus(business.id)" v-else class="tag tag-info">غیر فعال</span>
                      </td>
                      <td>{{ business.shamsi_created_at }}</td>
                      <td>
                        <template v-if="business.images.length">
                          <img class="icon" :src="ImageUrl+business.images[0].link" alt="">
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
                <label for="contact_number">شماره تماس</label>
                <input v-model="formData.contact_number" type="text" id="contact_number" class="form-control" placeholder="شماره تماس را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="telegram_id">آدرس تلگرامی</label>
                <input v-model="formData.telegram_id" type="text" id="telegram_id" class="form-control" placeholder="آدرس تلگرامی را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="instagram_id">آدرس اینستاگرامی</label>
                <input v-model="formData.instagram_id" type="text" id="instagram_id" class="form-control" placeholder="آدرس اینستاگرام را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="category_id">دسته بندی *</label>
                <select v-model="formData.category_id" id="category_id" class="form-control">
                  <option value="">انتخاب دسته بندی</option>
                  <option v-for="(category , key) in categories" :key="key" :value="category.id">{{category.title}}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <v-file-input
                  v-model="business_images"
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
          </div>
          <div class="card-footer">
            <button @click="CreateBusiness" class="btn btn-primary"><i class="fa fa-check"></i> ثبت</button>
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
                <label for="contact_number">شماره تماس</label>
                <input v-model="editFormData.contact_number" type="text" id="contact_number" class="form-control" placeholder="شماره تماس را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="telegram_id">آدرس تلگرامی</label>
                <input v-model="editFormData.telegram_id" type="text" id="telegram_id" class="form-control" placeholder="آدرس تلگرامی را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="instagram_id">آدرس اینستاگرامی</label>
                <input v-model="editFormData.instagram_id" type="text" id="instagram_id" class="form-control" placeholder="آدرس اینستاگرام را وارد کنید">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="category_id">دسته بندی *</label>
                <select v-model="editFormData.category_id" id="category_id" class="form-control">
                  <option value="">انتخاب دسته بندی</option>
                  <option v-for="(category , key) in categories" :key="key" :value="category.id">{{category.title}}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <v-file-input
                  v-model="business_images"
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
          </div>
          <div class="card-footer">
            <button @click="EditBusiness" class="btn btn-primary"><i class="fa fa-check"></i> ویرایش</button>
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
            <button @click="deleteBusiness" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
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
    
    <div v-if="sending" class="send-data-loading-wrapper">
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

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import ImageViewer from '../MyComponents/ImageViewer';
export default {
  components: { ImageViewer },
  name:"Businesses",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      array:[],
      businesses:{},
      categories:{},
      loaded:false,
      sending:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
      formData:{
        title:'',
        category_id:'',
        contact_number:'',
        instagram_id:'',
        telegram_id:'',
        description:''
      },
      business_images:null,
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
    getBusinesses(page){
      Axios.get(`businesses/list?page=${page}`)
      .then(res => {
        this.businesses = res.data.data;
        this.last_page = res.data.last_page;
        this.loaded = true;
        this.array = [];
      })
      .catch(err => {
        console.log(err)
      });
    },
    CreateBusiness(){
      this.sending = true;
      Axios.post(`businesses/create` , this.formData)
      .then(res => {
        this.uploadBusinessFiles(res.data.business.id);
        this.getBusinesses(1);
        this.createModal = false;
        this.sending = false;
        this.formData = {
          title:'',
          category_id:'',
          contact_number:'',
          instagram_id:'',
          telegram_id:'',
          description:'',
          images:null,
        };
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
        if(err.response.data.status == 'failed'){
          this.errorMessage = err.response.data.message;
          this.errorSnackbar = true;
          this.sending = false;
        }
      });
    },
    uploadBusinessFiles(business_id){
      if(this.business_images !== null){
        const data = new FormData();
        data.append('id', business_id);
        for(var i=0 ; i < this.business_images.length ; i++){
          let file = this.business_images[i];
          data.append('images['+ i +']', file);
        }
        Axios.post(`businesses/uploadFiles` , data,
        {
          headers: {
          'Content-Type': 'multipart/form-data'
          }
        })
        .then(res => {
          console.log(res.data)
          this.business_images = null;
        })
        .catch(err => {
          console.log(err.response)
          this.business_images = null;
        });
      }
    },
    getBusinessData(){
      Axios.get('businesses/business/'+this.array[0])
      .then(res => {
        this.editFormData = res.data;
        this.editModal = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    EditBusiness(){
      this.sending = true;
      Axios.post(`businesses/edit` , this.editFormData)
      .then(res => {
        this.array = [];
        this.uploadBusinessFiles(this.editFormData.id);
        this.getBusinesses(1);
        this.editModal = false;
        this.sending = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.editFormData = {};
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
        this.sending = false;
      });
    },
    deleteBusiness(){
      Axios.post('businesses/delete' , {array:this.array})
      .then(res => {
        this.array = [];
        this.getBusinesses(1);
        this.deleteModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    changeBusinessStatus(business_id){
      Axios.post('businesses/changeStatus' , {business_id})
      .then(res => {
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.getBusinesses(this.current_page);
      })
      .catch(err => {
        console.log(err)
      });
    },
    searchAdvertise(){
      this.loaded = false;
      const key = this.search_key
      Axios.post('businesses/search' , {key})
      .then(res => {
        this.businesses = res.data.data;
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
  watch:{
    current_page: function (page) {
      this.getBusinesses(page);
    }
  },
  mounted(){
    this.getBusinesses(this.current_page);
    this.getCategories();
  }
}
</script>
<style>

</style>