<template>
  <div class="">
    <ol class="breadcrumb m-b-0">
      <li class="breadcrumb-item">
        <router-link :to="{name:'Dashboard'}"> داشبورد </router-link>
      </li>
      <li class="breadcrumb-item active">مناطق</li>
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
                <button @click="getRegionData" v-if="array.length == 1" class="btn btn-warning m-y-1 m-l-1">
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
                      <th>اولویت</th>
                      <th>وضعیت</th>
                      <th>تاریخ ثبت</th>
                      <th>تاریخ ویرایش</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(region, key) in regions" :key="key">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="region.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ region.id }}</td>
                      <td>{{ region.title }}</td>
                      <td>{{ region.priority }}</td>
                      <td>
                        <span v-if="region.status" class="tag tag-success">فعال</span>
                        <span v-else class="tag tag-info">غیر فعال</span>
                      </td>
                      <td>{{ region.shamsi_created_at }}</td>
                      <td>{{ region.shamsi_updated_at }}</td>
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
              ایجاد <strong>منطقه</strong>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="title">عنوان *</label>
                <input v-model="formData.title" type="text" id="title" class="form-control" placeholder="عنوان منطقه را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="priority">اولویت *</label>
                <input v-model="formData.priority" type="number" id="priority" class="form-control" placeholder="شماره اولویت را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="status">وضعیت *</label>
                <select v-model="formData.status" id="status" class="form-control">
                  <option value="1">فعال</option>
                  <option value="0">غیر فعال</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="CreateRegion" class="btn btn-primary"><i class="fa fa-check"></i> ثبت</button>
            <button  @click="createModal = false" class="btn btn-danger"><i class="fa fa-times"></i>بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="editModal" scrollable max-width="900px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
              ویرایش <strong>منطقه</strong> با شناسه <b>{{editFormData.id}}</b>
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="title">عنوان *</label>
                <input v-model="editFormData.title" type="text" id="title" class="form-control" placeholder="عنوان منطقه را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="priority">اولویت *</label>
                <input v-model="editFormData.priority" type="number" id="priority" class="form-control" placeholder="شماره اولویت را وارد کنید">
              </div>
              <div class="form-group col-md-12">
                <label for="status">وضعیت *</label>
                <select v-model="editFormData.status" id="status" class="form-control">
                  <option value="1">فعال</option>
                  <option value="0">غیر فعال</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="EditRegion" class="btn btn-primary"><i class="fa fa-check"></i> ویرایش</button>
            <button  @click="editModal = false" class="btn btn-danger"><i class="fa fa-times"></i> بستن</button>
          </div>
        </div>
      </div>
    </v-dialog>
    
    <v-dialog v-model="deleteModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
            حذف منطقه
          </div>
          <div class="card-block">
            موارد انتخاب شده حذف شوند ؟
          </div>
          <div class="card-footer">
            <button @click="deleteRegion" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> حذف</button>
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
  name:"Regions",
  data(){
    return{
      array:[],
      regions:{},
      loaded:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
      formData:{
        title:'',
        priority:'',
        status:1
      },
      editFormData:{},
      errorSnackbar: false,
      errorMessage: '',
      successSnackbar: false,
      successMessage: ''    
    }
  },
  methods:{
    getRegions(page){
      Axios.get(`regions/list?page=${page}`)
      .then(res => {
        this.regions = res.data.data;
        this.last_page = res.data.last_page;
        this.loaded = true;
        this.array = [];
      })
      .catch(err => {
          console.log(err)
      });
    },
    CreateRegion(){
      Axios.post(`regions/create` , this.formData)
      .then(res => {
        this.getRegions(1);
        this.createModal = false;
        this.formData = {
          title:'',
          priority:'',
          status:1
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
    getRegionData(){
      Axios.get('regions/region/'+this.array[0])
      .then(res => {
        this.editFormData = res.data;
        this.editModal = true;
      })
      .catch(err => {
          console.log(err)
      });
    },
    EditRegion(){
      Axios.post(`regions/edit` , this.editFormData)
      .then(res => {
        this.array = [];
        this.getRegions(1);
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
    deleteRegion(){
      Axios.post('regions/delete' , {array:this.array})
      .then(res => {
        this.array = [];
        this.getRegions(1);
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
      this.getRegions(page);
    }
  },
  mounted(){
    this.getRegions(this.current_page);
  }
}
</script>
<style>

</style>