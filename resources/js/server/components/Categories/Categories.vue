<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">دسته بندی ها</li>
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
                <button @click="getCategoryData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                      <th>اسلاگ</th>
                      <th>نوع</th>
                      <th>تاریخ ثبت</th>
                      <th>تاریخ ویرایش</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(category, key) in categories" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="category.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ category.id }}</td>
                      <td>{{ category.title }}</td>
                      <td>{{ category.slug }}</td>
                      <td>{{ category.type }}</td>
                      <td>{{ category.shamsi_created_at }}</td>
                      <td>{{ category.shamsi_updated_at }}</td>
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
    <v-dialog v-model="createModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ایجاد <strong>دسته بندی</strong>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="title">عنوان  *</label>
                <input v-model="formData.title" type="text" id="title" class="form-control" placeholder="عنوان آگهی را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="slug">اسلاگ  *</label>
                <input v-model="formData.slug" type="text" id="slug" class="form-control" placeholder="عنوان آگهی را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="type">نوع  *</label>
                <select v-model="formData.type" id="type" class="form-control">
                  <option value="business">کسب و کار</option>
                  <option value="advertise">املاک</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreateCategory" class="btn btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="900px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ویرایش <strong>دسته بندی</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="title">عنوان  *</label>
                <input v-model="editFormData.title" type="text" id="title" class="form-control" placeholder="عنوان آگهی را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="slug">اسلاگ  *</label>
                <input v-model="editFormData.slug" type="text" id="slug" class="form-control" placeholder="عنوان آگهی را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="type">نوع  *</label>
                <select v-model="editFormData.type" id="type" class="form-control">
                  <option value="business">کسب و کار</option>
                  <option value="advertise">املاک</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="EditCategory" class="btn btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
            حذف دسته بندی
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deleteCategory" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
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

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
  name:"Categories",
  data(){
    return{
      array:[],
      categories:{},
      loaded:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
      formData:{
        title:'',
        slug:'',
        type:'business'
      },
      editFormData:{},
      errorSnackbar: false,
      errorMessage: '',
      successSnackbar: false,
      successMessage: ''    
    }
  },
  methods:{
    getCategories(page){
      Axios.get(`categories/list?page=${page}`)
      .then(res => {
        this.categories = res.data.data;
        this.last_page = res.data.last_page;
        this.loaded = true;
        this.array = [];
      })
      .catch(err => {
          console.log(err)
      });
    },
    CreateCategory(){
      Axios.post(`categories/create` , this.formData)
      .then(res => {
        this.getCategories(1);
        this.createModal = false;
        this.formData = {
          title:'',
          slug:'',
          type:'business'
        };
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        this.array = [];
      })
      .catch(err => {
        if(err.response.data.status == 'failed'){
          this.errorMessage = err.response.data.message;
          this.errorSnackbar = true;
        }
      });
    },
    getCategoryData(){
      Axios.get('categories/category/'+this.array[0])
      .then(res => {
        this.editFormData = res.data;
        this.editModal = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    EditCategory(){
      Axios.post(`categories/edit` , this.editFormData)
      .then(res => {
        this.array = [];
        this.getCategories(1);
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
    deleteCategory(){
      Axios.post('categories/delete' , {array:this.array})
      .then(res => {
        this.array = [];
        this.getCategories(1);
        this.deleteModal = false;
        this.successMessage = res.data.message;
        this.successSnackbar = true;
      })
      .catch(err => {
          console.log(err)
      });
    }
  },
  watch:{
    current_page: function (page) {
      this.getCategories(page);
    }
  },
  mounted(){
    this.getCategories(this.current_page);
  }
}
</script>
<style>

</style>