<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">نقش ها</li>
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
                <button @click="getRoleData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                      <th>دسترسی ها</th>
                      <th>تاریخ ثبت</th>
                      <th>تاریخ ویرایش</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(role, key) in roles" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="role.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ role.id }}</td>
                      <td>{{ role.name }}</td>
                      <td>
                        <span v-for="(permission,key) in role.permissions" :key="key">{{permission.title}} ,, </span>
                      </td>
                      <td>{{ role.shamsi_created_at }}</td>
                      <td>{{ role.shamsi_updated_at }}</td>
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
              ایجاد <strong>نقش</strong>
          </div>
          <div class="card-block">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="name">نام *</label>
                    <input v-model="formData.name" type="text" id="name" class="form-control" placeholder="نام نقش را وارد کنید">
                </div>
                <div class="form-group col-md-12">
                    <label for="permissions">دسترسی ها</label>
                    <v-select
                    v-model="formData.permissions"
                    :items="permissions"
                    item-text="title"
                    item-value="name"
                    multiple
                    outlined
                    dense
                  ></v-select>
                </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreateRole" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="900px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ویرایش <strong>نقش</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                  <label for="name">نام *</label>
                  <input v-model="editFormData.name" type="text" id="name" class="form-control" placeholder="نام نقش را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="permissions">دسترسی ها</label>
                <v-select
                  v-model="editFormData.rolePermissions"
                  :items="permissions"
                  item-text="title"
                  item-value="name"
                  multiple
                  outlined
                  dense
                ></v-select>
              </div>
            </div>
          </div> 
          <div class="card-footer">
            <button @click="EditRole" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              حذف نقش
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deleteRole" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
            <button  @click="deleteModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>

    <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
      <div>{{errors.name}}</div>
      <div>{{errors.permissions}}</div>
    </v-snackbar>
    <v-snackbar color="green" :timeout="4000" v-model="successSnackbar">
      <div>{{successMessage}}</div>
    </v-snackbar>
    <v-snackbar color="red" :timeout="4000" v-model="hasError">
      <div>{{hasErrorMessage}}</div>
    </v-snackbar>

  </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
    name:"Roles",
    data(){
      return{
        array:[],
        roles:{},
        permissions:{},
        loaded:false,
        current_page:1,
        last_page:1,
        createModal:false,
        editModal:false,
        deleteModal:false,
        formData:{
          name:'',
          permissions:[]
        },
        editFormData:{},
        errors:{
          name:null,
          permissions:null
        },
        hasError: false,
        errorSnackbar: false,
        successSnackbar: false,
        successMessage: '',
        hasErrorMessage: '',
      }
    },
    methods:{
      getRoles(page){
        Axios.get(`roles/list?page=${page}`)
        .then(res => {
          this.roles = res.data.roles.data;
          this.permissions = res.data.permissions;
          this.last_page = res.data.roles.last_page;
          this.loaded = true;
          this.array = [];
        })
        .catch(err => {
          console.log(err)
        });
      },
      CreateRole(){
        Axios.post(`roles/create` , this.formData)
        .then(res => {
          this.array = [];
          this.getRoles(1);
          this.createModal = false;
          this.formData = {
            name:'',
            permissions:[]
          };
          this.successMessage = res.data.message;
          this.successSnackbar = true;
        })
        .catch(err => {
          this.errors = err.response.data.errors;
          this.errorSnackbar = true;
        });
      },
      getRoleData(){
        Axios.get('roles/role/'+this.array[0])
        .then(res => {
          this.editFormData = res.data;
          this.editModal = true;
        })
        .catch(err => {
            console.log(err)
        });
      },
      EditRole(){
        Axios.post(`roles/edit` , this.editFormData)
        .then(res => {
          this.array = [];
          this.getRoles(1);
          this.editModal = false;
          this.successMessage = res.data.message;
          this.successSnackbar = true;
        })
        .catch(err => {
          this.errors = err.response.data.errors;
          this.errorSnackbar = true;
        });
      },
      deleteRole(){
        Axios.post('roles/delete' , {array:this.array})
        .then(res => {
          this.array = [];
          this.getRoles(1);
          this.deleteModal = false;
          this.successMessage = res.data.message;
          this.successSnackbar = true;
        })
        .catch(err => {
          this.hasErrorMessage = err.response.data.error;
          this.hasError = true;
        });
      }
    },
    watch:{
      current_page: function (page) {
        this.getRoles(page);
      }
    },
    mounted(){
      this.getRoles(this.current_page);
    }
}
</script>
<style>

</style>