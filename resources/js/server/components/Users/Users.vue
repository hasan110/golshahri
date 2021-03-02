<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">کاربران</li>
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
                <button @click="getUserData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                  :total-visible="6"
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
                      <th>نام</th>
                      <th>شماره تلفن</th>
                      <th>وضعیت</th>
                      <th>تاریخ ثبت</th>
                      <th>تصویر</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(user, key) in users" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="user.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ user.id }}</td>
                      <td>{{ user.name }}</td>
                      <td>{{ user.number }}</td>
                      <td>
                        <span @click="changeUserBlockStatus(user.id)" v-if="user.is_block == 0" class="tag tag-success">فعال</span>
                        <span @click="changeUserBlockStatus(user.id)" v-else class="tag tag-info">بلاک</span>
                      </td>
                      <td>{{ user.shamsi_created_at }}</td>
                      <td v-if="user.image">
                        <img @click="showImage(ImageUrl+user.image)" class="icon" :src="ImageUrl+user.image" alt="">
                      </td>
                      <td v-else>
                        -
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
              ایجاد <strong>کاربر</strong>
          </div>
          <div class="card-block">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">نام *</label>
                    <input v-model="formData.name" type="text" id="name" class="form-control" placeholder="نام کاربر را وارد کنید">
                </div>
                <div class="form-group col-md-6">
                    <label for="number">شماره تلفن *</label>
                    <input v-model="formData.number" type="number" id="number" name="text-input" class="form-control" placeholder="شماره تلفن کاربر را وارد کنید">
                </div>
            </div>
            <div class="form-group row m-x-2">
              <div class="col-md-8">
                <label for="image">تصویر پروفایل</label>
                <input class="form-control" type="file" id="image" ref="image" @change="selectUserImage">
              </div>
              <div class="col-md-4">
                <div class="thumbnail-preview">
                  <img v-show="thumbnail" :src="thumbnail" alt="">
                </div>
              </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="is_block">وضعیت</label>
                    <select v-model="formData.is_block" id="is_block" class="form-control">
                      <option value="0">فعال</option>
                      <option value="1">بلاک</option>
                    </select>
                </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreateUser" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="900px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ویرایش <strong>کاربر</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">نام *</label>
                    <input v-model="editFormData.name" type="text" id="name" class="form-control" placeholder="نام کاربر را وارد کنید">
                </div>
                <div class="form-group col-md-6">
                    <label for="number">شماره تلفن *</label>
                    <input v-model="editFormData.number" type="number" id="number" name="text-input" class="form-control" placeholder="شماره تلفن کاربر را وارد کنید">
                </div>
            </div>

            <div class="form-group row m-x-2">
              <div class="col-md-8">
                <label for="image">تصویر پروفایل</label>
                <input class="form-control" type="file" id="editimage" ref="editimage" @change="editUserImage">
              </div>
              <div class="col-md-4">
                <div class="thumbnail-preview">
                  <img v-show="thumbnailedit" :src="thumbnailedit" alt="">
                </div>
              </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="is_block">وضعیت</label>
                    <select v-model="editFormData.is_block" id="is_block" class="form-control">
                      <option value="0">فعال</option>
                      <option value="1">بلاک</option>
                    </select>
                </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="EditUser" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              حذف کاربر
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deleteUser" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
            <button  @click="deleteModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div>{{errors.name}}</div>
      <div>{{errors.number}}</div>
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
  name:"Users",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      thumbnail:false,
      thumbnailedit:false,
      array:[],
      users:{},
      loaded:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
      formData:{
        name:'',
        number:'',
        image:null,
        is_block:0,
      },
      editFormData:{},
      errors:{
        name:null,
        number:null,
      },
      hasError: false,
      errorSnackbar: false,
      successSnackbar: false,
      successMessage: '',    
    }
  },
  methods:{
    showImage(Image_url){
      this.imagePreviewUrl = Image_url;
      this.preview = true;
    },
    selectUserImage(event) {
      this.formData.image = this.$refs.image.files[0];
      const file = event.target.files[0];
      this.thumbnail = URL.createObjectURL(file);
    },
    editUserImage(event) {
      this.editFormData.image = this.$refs.editimage.files[0];
      const file = event.target.files[0];
      this.thumbnailedit = URL.createObjectURL(file);
    },
    getUsers(page){
      Axios.get(`users/list?page=${page}`)
      .then(res => {
          this.users = res.data.data;
          this.last_page = res.data.last_page;
          this.loaded = true;
          this.array = [];
      })
      .catch(err => {
          console.log(err)
      });
    },
    CreateUser(){
      const data = new FormData();
      data.append('name', this.formData.name);
      data.append('number', this.formData.number);
      data.append('image', this.formData.image);
      data.append('is_block', this.formData.is_block);
      Axios.post(`users/create` , data,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.array = [];
        this.getUsers(1);
        this.createModal = false;
        this.formData = {
          name:'',
          number:'',
          image:null,
          is_block:0,
        };
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.thumbnail = false;
        this.$refs.image.value=null;
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    },
    getUserData(){
      Axios.get('users/user/'+this.array[0])
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
    EditUser(){
      const editdata = new FormData();
      editdata.append('id', this.editFormData.id);
      editdata.append('name', this.editFormData.name);
      editdata.append('number', this.editFormData.number);
      editdata.append('image', this.editFormData.image);
      editdata.append('is_block', this.editFormData.is_block);
      Axios.post(`users/edit` , editdata,
      {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      })
      .then(res => {
        this.array = [];
        this.getUsers(1);
        this.editModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
        this.errors = err.response.data.errors;
        this.errorSnackbar = true;
      });
    },
    deleteUser(){
      Axios.post('users/delete' , {array:this.array})
      .then(res => {
        this.array = [];
        this.getUsers(1);
        this.deleteModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    changeUserBlockStatus(user_id){
      Axios.post('users/changeBlockStatus' , {user_id})
      .then(res => {
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.getUsers(this.current_page);
      })
      .catch(err => {
        console.log(err)
      });
    }
  },
  watch:{
    current_page: function (page) {
      this.getUsers(page);
    }
  },
  mounted(){
    this.getUsers(this.current_page);
  }
}
</script>
<style>

</style>