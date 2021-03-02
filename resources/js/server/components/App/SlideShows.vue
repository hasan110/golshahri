<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">اسلایدشو ها</li>
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
                <button @click="getSlideShowData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                <table class="table table-customize table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th></th>
                      <th>ردیف</th>
                      <th>شناسه</th>
                      <th>عنوان</th>
                      <th>مکان</th>
                      <th>پیش فرض</th>
                      <th>وضعیت</th>
                      <th>تاریخ انتشار</th>
                      <th>تاریخ انقضا</th>
                      <th>تاریخ ثبت</th>
                      <th>تاریخ ویرایش</th>
                      <th>تصویر</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(item, key) in slideshows" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="item.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ item.id }}</td>
                      <td>{{ item.title }}</td>
                      <td v-if="item.place=='خانه'">
                        <b class="tag tag-lg tag-warning">
                          <i class="fa fa-home"></i>
                        </b>
                      </td>
                      <td v-else>
                        {{ item.place }}
                      </td>
                      <td v-if="item.default"><i class="fa fa-check text-success"></i></td>
                      <td v-else><i class="fa fa-times text-danger"></i></td>
                      <td>
                        <span @click="changeSlideShowStatus(item.id)" v-if="item.status" class="tag tag-info">فعال</span>
                        <span @click="changeSlideShowStatus(item.id)" v-else class="tag tag-danger">غیر فعال</span>
                      </td>
                      <td>{{ item.shamsi_release }}</td>
                      <td>{{ item.shamsi_expiry }}</td>
                      <td>{{ item.shamsi_created_at }}</td>
                      <td>{{ item.shamsi_updated_at }}</td>
                      <td v-if="item.image">
                        <img @click="showImage(ImageUrl+item.image)" class="icon" :src="ImageUrl+item.image" alt="">
                      </td>
                      <td v-else> - </td>
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
    <v-dialog v-model="createModal" scrollable max-width="500px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ایجاد <strong>اسلایدشو</strong>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-6">
                  <label for="title">عنوان *</label>
                  <input v-model="formData.title" type="text" id="title" class="form-control" placeholder="عنوان اسلایدشو را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="title">مکان *</label>
                <select v-model="formData.place" id="category" class="form-control">
                  <option value="0">خانه</option>
                  <option v-for="(category, key) in categories" :key="key" :value="category.id">{{category.title}}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                  <label for="image">تصویر اسلایدشو</label>
                  <input class="form-control" type="file" id="image" ref="image" @change="selectSlideShowImage">
              </div>
              <div class="form-group col-md-6">
                <div class="thumbnail-preview">
                <img v-show="thumbnail" :src="thumbnail" alt="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-3">
                پیش فرض : <v-checkbox color="success" v-model="formData.default" :value="1"></v-checkbox>
              </div>
              <div class="form-group col-md-3">
                فعال ؟ <v-checkbox color="success" v-model="formData.status" :value="1"></v-checkbox>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="release">تاریخ انتشار</label><br>
                <custom-date-picker ref="release" v-model="formData.release"></custom-date-picker>
              </div>
              <div class="form-group col-md-6">
                <label for="expiry">تاریخ انقضا</label><br>
                <custom-date-picker ref="expiry" v-model="formData.expiry"></custom-date-picker>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreateSlideShow" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="500px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ویرایش <strong>اسلایدشو</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="title">عنوان *</label>
                <input v-model="editFormData.title" type="text" id="title" class="form-control" placeholder="عنوان اسلایدشو را وارد کنید">
              </div>
              <div class="form-group col-md-6">
                <label for="title">مکان *</label>
                <select v-model="editFormData.place" id="category" class="form-control">
                  <option value="0">خانه</option>
                  <option v-for="(category, key) in categories" :key="key" :value="category.id">{{category.title}}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="editimage">تصویر اسلایدشو</label>
                <input class="form-control" type="file" id="editimage" ref="editimage" @change="editSlideShowImage">
              </div>
              <div class="form-group col-md-6">
                <div class="thumbnail-preview">
                <img v-show="thumbnailedit" :src="thumbnailedit" alt="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-3">
                پیش فرض : <v-checkbox color="success" v-model="editFormData.default" :value="1"></v-checkbox>
              </div>
              <div class="form-group col-md-3">
                وضعیت : <v-checkbox color="success" v-model="editFormData.status" :value="1"></v-checkbox>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="release">تاریخ انتشار</label><br>
                <custom-date-picker v-model="editFormData.shamsi_release"></custom-date-picker>
              </div>
              <div class="form-group col-md-6">
                <label for="expiry">تاریخ انقضا</label><br>
                <custom-date-picker v-model="editFormData.shamsi_expiry"></custom-date-picker>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="EditSlideShow" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              حذف اسلایدشو
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deleteSlideShows" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
            <button  @click="deleteModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>

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
  name:"SlideShows",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      thumbnail:false,
      thumbnailedit:false,
      array:[],
      slideshows:{},
      loaded:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
      formData:{
        title:'',
        place:0,
        image:null,
        expiry:null,
        release:null,
        status:1,
        default:1,
      },
      editFormData:{},
      categories:{},
      errors:{
        title:null,
        place:null,
        release:null,
        expiry:null,
      },
      hasError: false,
      errorSnackbar: false,
      successSnackbar: false,
      successMessage: '',
      config: {
      }       
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
    getSlideShows(page){
      Axios.get(`slideshows/list?page=${page}`)
      .then(res => {
          this.slideshows = res.data.data;
          this.last_page = res.data.last_page;
          this.loaded = true;
          this.array = [];
      })
      .catch(err => {
          console.log(err)
      });
    },
    getPostCategories(){
      Axios.get('posts/categories/all')
      .then(res => {
          this.categories = res.data;
      })
      .catch(err => {
          console.log(err)
      });
    },
    CreateSlideShow(){
      const data = new FormData();
      data.append('title', this.formData.title);
      data.append('place', this.formData.place);
      data.append('image', this.formData.image);
      data.append('expiry', this.formData.expiry);
      data.append('release', this.formData.release);
      data.append('status', this.formData.status);
      data.append('default', this.formData.default);
      Axios.post(`slideshows/create` , data,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.array = [];
        this.getSlideShows(1);
        this.createModal = false;
        this.formData = {
          title:'',
          place:0,
          image:null,
          expiry:'',
          release:'',
          status:1,
          default:1,
        };
        this.$refs["expiry"].value = "";
        this.$refs["release"].value = "";
        this.$refs.image.value=null;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    },
    getSlideShowData(){
      Axios.get('slideshows/slideshow/'+this.array[0])
      .then(res => {
        this.editFormData = res.data;
        this.editModal = true;
        this.thumbnailedit = false;
        this.$refs.editimage.value=null;
      })
      .catch(err => {
          console.log(err)
      });
    },
    EditSlideShow(){
      const editdata = new FormData();
      editdata.append('id', this.editFormData.id);
      editdata.append('title', this.editFormData.title);
      editdata.append('place', this.editFormData.place);
      editdata.append('image', this.editFormData.image);
      editdata.append('shamsi_expiry', this.editFormData.shamsi_expiry);
      editdata.append('shamsi_release', this.editFormData.shamsi_release);
      editdata.append('status', this.editFormData.status);
      editdata.append('default', this.editFormData.default);
      Axios.post(`slideshows/edit` , editdata,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.array = [];
        this.getSlideShows(1);
        this.$refs.editimage.value=null;
        this.editModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    },
    deleteSlideShows(){
      Axios.post('slideshows/delete' , {array:this.array})
      .then(res => {
        this.array = [];
        this.getSlideShows(1);
        this.deleteModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    changeSlideShowStatus(slideshow_id){
      Axios.post('slideshows/changeStatus' , {slideshow_id})
      .then(res => {
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.getSlideShows(this.current_page);
      })
      .catch(err => {
          console.log(err)
      });
    }
  },
  watch:{
    current_page: function (page) {
      this.getSlideShows(page);
    }
  },
  mounted(){
    this.getSlideShows(this.current_page);
    this.getPostCategories();
  }
}
</script>
<style>
.tag{
  cursor: pointer;
}
</style>