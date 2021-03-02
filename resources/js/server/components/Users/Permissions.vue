<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">دسترسی ها</li>
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
                <button @click="getPermissionData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                      <th>نام</th>
                      <th>عنوان</th>
                      <th>تاریخ ثبت</th>
                      <th>تاریخ ویرایش</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(permission, key) in permissions" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="permission.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ permission.id }}</td>
                      <td>{{ permission.name }}</td>
                      <td>{{ permission.title }}</td>
                      <td>{{ permission.shamsi_created_at }}</td>
                      <td>{{ permission.shamsi_updated_at }}</td>
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
            ایجاد <strong>دسترسی</strong>
          </div>
          <div class="card-block">
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="name">نام *</label>
                  <input v-model="formData.name" type="text" id="name" class="form-control" placeholder="نام دسترسی را به لاتین وارد کنید">
                </div>
                <div class="form-group col-md-12">
                  <label for="title">عنوان *</label>
                  <input v-model="formData.title" type="text" id="title" class="form-control" placeholder="عنوان دسترسی را به فارسی وارد کنید">
                </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreatePermission" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="500px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
            ویرایش <strong>دسترسی</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="name">نام *</label>
                <input v-model="editFormData.name" type="text" id="name" class="form-control" placeholder="نام دسترسی را به لاتین وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="title">عنوان *</label>
                <input v-model="editFormData.title" type="text" id="title" class="form-control" placeholder="عنوان دسترسی را به فارسی وارد کنید">
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="EditPermission" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              حذف دسترسی
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deletePermission" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
            <button  @click="deleteModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div>{{errors.name}}</div>
      <div>{{errors.title}}</div>
    </v-snackbar>
    <v-snackbar color="green" :timeout="4000" v-model="successSnackbar">
      <div>{{successMessage}}</div>
    </v-snackbar>

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
    name:"Permissions",
    data(){
      return{
        array:[],
        permissions:{},
        loaded:false,
        current_page:1,
        last_page:1,
        createModal:false,
        editModal:false,
        deleteModal:false,
        formData:{
          name:'',
          title:''
        },
        editFormData:{},
        errors:{
          name:null,
          title:null
        },
        hasError: false,
        errorSnackbar: false,
        successSnackbar: false,
        successMessage: '',    
      }
    },
    methods:{
      getPermissions(page){
        Axios.get(`permissions/list?page=${page}`)
        .then(res => {
          this.permissions = res.data.data;
          this.last_page = res.data.last_page;
          this.loaded = true;
          this.array = [];
        })
        .catch(err => {
          console.log(err)
        });
      },
      CreatePermission(){
        const data = new FormData();
        data.append('name', this.formData.name);
        data.append('title', this.formData.title);
        Axios.post(`permissions/create` , data)
        .then(res => {
          this.array = [];
          this.getPermissions(1);
          this.createModal = false;
          this.formData = {
            name:'',
            title:''
          };
          this.successMessage = res.data.message;
          this.successSnackbar = true;
        })
        .catch(err => {
          this.errors = err.response.data.errors;
          this.errorSnackbar = true;
        });
      },
      getPermissionData(){
        Axios.get('permissions/permission/'+this.array[0])
        .then(res => {
          this.editFormData = res.data;
          this.editModal = true;
        })
        .catch(err => {
            console.log(err)
        });
      },
      EditPermission(){
        const editdata = new FormData();
        editdata.append('id', this.editFormData.id);
        editdata.append('name', this.editFormData.name);
        editdata.append('title', this.editFormData.title);
        Axios.post(`permissions/edit` , editdata)
        .then(res => {
          this.array = [];
          this.getPermissions(1);
          this.editModal = false;
          this.successMessage = res.data.message;
          this.successSnackbar = true;
        })
        .catch(err => {
          this.errors = err.response.data.errors;
          this.errorSnackbar = true;
        });
      },
      deletePermission(){
        Axios.post('permissions/delete' , {array:this.array})
        .then(res => {
          this.array = [];
          this.getPermissions(1);
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
        this.getPermissions(page);
      }
    },
    mounted(){
      this.getPermissions(this.current_page);
    }
}
</script>
<style>

</style>