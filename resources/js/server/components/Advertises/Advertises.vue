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
                <button @click="filterModal = true" class="btn btn-success m-y-1 m-l-1">
                  <i class="fa fa-filter"></i>
                </button>
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
                      <th>متراژ</th>
                      <th>اطلاعات قیمت</th>
                      <th>بازدید</th>
                      <th>وضعیت</th>
                      <th>بیشتر</th>
                      <th>تصویر</th>
                    </tr>
                  </thead>
                  <div class="loading" v-show="!loaded">
                    <v-progress-linear indeterminate color="cyan"></v-progress-linear>
                  </div>
                  <tbody>
                    <tr v-for="(advertise, key) in advertises" :key="key" style="">
                      <td>
                        <v-checkbox v-model="array" color="blue" :value="advertise.id"></v-checkbox>
                      </td>
                      <td>{{ key+1 }}</td>
                      <td>{{ advertise.id }}</td>
                      <td>
                        <template v-if="advertise.type == 1"><i class="fa fa-circle text-success"></i></template>
                        <template v-else-if="advertise.type == 2"><i class="fa fa-circle text-danger"></i></template>
                        <template v-else-if="advertise.type == 3"><i class="fa fa-circle text-primary"></i></template>
                        <template v-else-if="advertise.type == 4"><i class="fa fa-circle text-warning"></i></template>
                        <template v-else-if="advertise.type == 5"><i class="fa fa-circle text-dark"></i></template>
                        {{ advertise.title }}
                      </td>
                      <td>{{ advertise.region.title }} - {{ advertise.street }}</td>
                      <td>
                        <template v-if="advertise.user">
                          {{ advertise.user.name }}-{{ advertise.user.number }}
                        </template>
                        <template v-else-if="advertise.admin">
                          <i class="fa fa-user"></i> - {{ advertise.admin.name }}
                        </template>
                        <template v-else>نا مشخص</template>
                      </td>
                      <td>{{ advertise.area }}</td>
                      <td>
                        <template v-if="advertise.type == 1">{{ advertise.price }} میلیون</template>
                        <template v-else-if="advertise.type == 2">{{ advertise.rent }} رهن کامل</template>
                        <template v-else-if="advertise.type == 3">{{ advertise.rent }} رهن با {{ advertise.meed }} اجاره</template>
                        <template v-else-if="advertise.type == 4">حدود {{ advertise.price }} میلیون</template>
                        <template v-else-if="advertise.type == 5">حدود {{ advertise.rent }} - {{ advertise.meed }} </template>
                      </td>
                      <td>{{ advertise.view_count }}</td>
                      <td>
                        <span @click="changeAdvertiseStatus(advertise.id)" v-if="advertise.confirmed" class="tag tag-success">فعال</span>
                        <span @click="changeAdvertiseStatus(advertise.id)" v-else class="tag tag-info">غیر فعال</span>
                      </td>
                      <td>
                        <v-tooltip right>
                          <template v-slot:activator="{ on, attrs }">
                            <b v-bind="attrs" v-on="on" class="badge badge-light">
                              <i style="font-size:22px;" class="fa fa-info-circle"></i>
                            </b>
                          </template>
                          <div><i class="fa fa-check"></i> {{ advertise.shamsi_created_at }}</div>
                          <div><i class="fa fa-edit"></i> {{ advertise.shamsi_updated_at }}</div>
                        </v-tooltip>
                      </td>
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
                <label for="type">نوع آگهی <span class="text-danger">*</span></label>
                <select v-model="formData.type" id="type" class="form-control">
                  <option value="1">فروش</option>
                  <option value="2">رهن کامل</option>
                  <option value="3">رهن و اجاره</option>
                  <option value="4">درخواست خرید</option>
                  <option value="5">درخواست رهن و اجاره</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="title">عنوان آگهی <span class="text-danger">*</span></label>
                <input v-model="formData.title" type="text" id="title" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="status">نوع ملک <span class="text-danger">*</span></label>
                <select v-model="formData.status" id="status" class="form-control">
                  <option value="منزل">منزل</option>
                  <option value="مغازه">مغازه</option>
                  <option value="زمین">زمین</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="region_id">
                  <template v-if="formData.type == 1 || formData.type == 2 || formData.type == 3">انتخاب منطقه</template>
                  <template v-else >ترجیحا کدام منطقه؟</template>
                  <span class="text-danger">*</span>
                </label>
                <select v-model="formData.region_id" id="region_id" class="form-control">
                  <option v-for="(region , key) in regions" :key="key" :value="region.id">{{region.title}}</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div v-show="formData.type == 1 || formData.type == 2 || formData.type == 3" class="form-group col-md-6">
                <label for="street">خیابان <span class="text-danger">*</span></label>
                <input v-model="formData.street" type="text" id="street" class="form-control">
              </div>
              <div v-show="formData.type == 1 || formData.type == 4" class="form-group col-md-6">
                <label for="price">
                  <template v-if="formData.type == 1 || formData.type == 2 || formData.type == 3">قیمت</template>
                  <template v-else >حدود قیمت</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="formData.price" type="number" id="price" @input="InsertJustNumber" min="0" class="form-control">
              </div>
              <div v-show="formData.status !== 'زمین'" class="form-group col-md-6">
                <label for="lifetime_state">وضعیت عمر منزل *</label>
                <select v-model="formData.lifetime_state" id="lifetime_state" class="form-control">
                  <option value="نوساز">نوساز</option>
                  <option value="معمولی">معمولی</option>
                  <option value="قدیمی ساز">قدیمی ساز</option>
                </select>
              </div>
              <div v-show="formData.status == 'منزل' && formData.type == 1" class="form-group col-md-6">
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
                <label for="lifetime_state">موقعیت ملک <span class="text-danger">*</span></label>
                <v-radio-group v-model="formData.is_in_lane" >
                  <v-radio label="داخل خیابان" :value="0"></v-radio>
                  <v-radio label="داخل کوچه" :value="1"></v-radio>
                </v-radio-group>
              </div>
              <div v-show="formData.type == 1 && formData.status !== 'مغازه'" class="form-group col-md-6">
                <label for="lane_width">عرض کوچه / خیابان</label>
                <input v-model="formData.lane_width" type="number" step="any" @input="InsertJustNumber" min="0" id="lane_width" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="area">
                  <template v-if="formData.type == 1 || formData.type == 2 || formData.type == 3">متراژ کل</template>
                  <template v-else >حدود متراژ</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="formData.area" type="number" @input="InsertJustNumber" min="0" id="area" class="form-control">
              </div>
              <div v-show="formData.type == 1" class="form-group col-md-4">
                <label for="length_house">طول حاشیه</label>
                <input v-model="formData.length_house" type="number" step="any" @input="InsertJustNumber" min="0" id="length_house" class="form-control">
              </div>
              <div v-show="formData.status == 'منزل'" class="form-group col-md-4">
                <label for="roof_number">تعداد طبقات</label>
                <input v-model="formData.roof_number" type="number" step="any" @input="InsertJustNumber" min="0" id="roof_number" class="form-control">
              </div>
            </div>
            <div class="row">
              <div v-show="formData.type == 2 || formData.type == 3 || formData.type == 5" class="form-group col-md-6">
                <label for="rent">
                  <template v-if="formData.type == 2 || formData.type == 3">میزان رهن</template>
                  <template v-else >تا چقدر رهن ؟</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="formData.rent" type="number" @input="InsertJustNumber" min="0" id="rent" class="form-control">
              </div>
              <div v-show="formData.type == 3 || formData.type == 5" class="form-group col-md-6">
                <label for="meed"> 
                  <template v-if="formData.type == 3">میزان اجاره</template>
                  <template v-else >تا چقدر اجاره ؟</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="formData.meed" type="number" @input="InsertJustNumber" min="0" id="meed" class="form-control">
              </div>
            </div>
            <div class="row" v-show="formData.type == 1 || formData.type == 2 || formData.type == 3">
              <div class="col-12">
                <v-file-input
                  v-model="advertise_images"
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
              <div v-show="formData.type == 1 || formData.type == 2 || formData.type == 3" class="form-group col-md-12">
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
                <label for="title">عنوان آگهی <span class="text-danger">*</span></label>
                <input v-model="editFormData.title" type="text" id="title" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="type">نوع آگهی <span class="text-danger">*</span></label>
                <select v-model="editFormData.type" id="type" class="form-control">
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
                <label for="status">نوع ملک <span class="text-danger">*</span></label>
                <select v-model="editFormData.status" id="status" class="form-control">
                  <option value="منزل">منزل</option>
                  <option value="مغازه">مغازه</option>
                  <option value="زمین">زمین</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="region_id">
                  <template v-if="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3">ویرایش منطقه</template>
                  <template v-else >ویرایش حدود منطقه؟</template>
                  <span class="text-danger">*</span>
                </label>
                <select v-model="editFormData.region_id" id="region_id" class="form-control">
                  <option v-for="(region , key) in regions" :key="key" :value="region.id">{{region.title}}</option>
                </select>
              </div>
            </div>
            
            <div class="row">
              <div v-show="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3" class="form-group col-md-6">
                <label for="street">خیابان *</label>
                <input v-model="editFormData.street" type="text" id="street" class="form-control">
              </div>
              <div v-show="editFormData.type == 1 || editFormData.type == 4" class="form-group col-md-6">
                <label for="price">
                  <template v-if="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3">قیمت</template>
                  <template v-else >حدود قیمت</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="editFormData.price" type="number" @input="InsertJustNumber" min="0" id="price" class="form-control">
              </div>
              <div v-show="editFormData.status !== 'زمین'" class="form-group col-md-6">
                <label for="lifetime_state">وضعیت عمر منزل *</label>
                <select v-model="editFormData.lifetime_state" id="lifetime_state" class="form-control">
                  <option value="نوساز">نوساز</option>
                  <option value="معمولی">معمولی</option>
                  <option value="قدیمی ساز">قدیمی ساز</option>
                </select>
              </div>
              <div v-if="editFormData.status == 'منزل'" v-show="editFormData.type == 1 || editFormData.type == 4" class="form-group col-md-6">
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
                <label for="is_in_lane">موقعیت ملک <span class="text-danger">*</span></label>
                <v-radio-group v-model="editFormData.is_in_lane" >
                  <v-radio label="داخل خیابان" :value="0"></v-radio>
                  <v-radio label="داخل کوچه" :value="1"></v-radio>
                </v-radio-group>
              </div>
              <div v-show="editFormData.type == 1 && editFormData.status !== 'مغازه'" class="form-group col-md-6">
                <label for="lane_width">عرض کوچه / خیابان</label>
                <input v-model="editFormData.lane_width" type="number" step="any" @input="InsertJustNumber" min="0" id="lane_width" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="area">
                  <template v-if="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3">متراژ کل</template>
                  <template v-else >حدود متراژ</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="editFormData.area" type="number" @input="InsertJustNumber" min="0" id="area" class="form-control">
              </div>
              <div v-show="editFormData.type == 1" class="form-group col-md-4">
                <label for="length_house">طول حاشیه</label>
                <input v-model="editFormData.length_house" type="number" step="any" @input="InsertJustNumber" min="0" id="length_house" class="form-control">
              </div>
              <div v-show="editFormData.status == 'منزل'" class="form-group col-md-4">
                <label for="roof_number">تعداد طبقات</label>
                <input v-model="editFormData.roof_number" type="number" step="any" @input="InsertJustNumber" min="0" id="roof_number" class="form-control">
              </div>
            </div>
            <div class="row">
              <div v-show="editFormData.type == 2 || editFormData.type == 3 || editFormData.type == 5" class="form-group col-md-6">
                <label for="rent">
                  <template v-if="editFormData.type == 2 || editFormData.type == 3">میزان رهن</template>
                  <template v-else >تا چقدر رهن ؟</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="editFormData.rent" type="number" @input="InsertJustNumber" min="0" id="rent" class="form-control">
              </div>
              <div v-show="editFormData.type == 3 || editFormData.type == 5" class="form-group col-md-6">
                <label for="meed">
                  <template v-if="editFormData.type == 3">میزان اجاره</template>
                  <template v-else >تا چقدر اجاره ؟</template>
                  <span class="text-danger">*</span>
                </label>
                <input v-model="editFormData.meed" type="number" @input="InsertJustNumber" min="0" id="meed" class="form-control">
              </div>
            </div>
            <div class="row" v-show="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3">
              <div class="col-12">
                <v-file-input
                  v-model="advertise_images"
                  color="grey darken-3"
                  counter
                  label="انتخاب تصاویر جدید"
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
            <div class="row" v-show="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3">
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
              <div v-show="editFormData.type == 1 || editFormData.type == 2 || editFormData.type == 3" class="form-group col-md-12">
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

    <v-dialog v-model="filterModal" scrollable max-width="400px">
      <div>
        <div class="card m-b-0">
          <div class="card-header">
            فیلتر
          </div>
          <div class="card-block">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="status">نوع آگهی</label>
                <select v-model="FilterFormData.type" id="status" class="form-control">
                  <option value="0">انتخاب کنید</option>
                  <option value="1">فروش</option>
                  <option value="2">رهن کامل</option>
                  <option value="3">رهن و اجاره</option>
                  <option value="4">درخواست خرید</option>
                  <option value="5">درخواست رهن و اجاره</option>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label for="status">نوع ملک</label>
                <select v-model="FilterFormData.status" id="status" class="form-control">
                  <option value="">انتخاب کنید</option>
                  <option value="منزل">منزل</option>
                  <option value="مغازه">مغازه</option>
                  <option value="زمین">زمین</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button @click="FilterAdvertise" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> اعمال فیلتر</button>
            <button  @click="filterModal = false" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> بستن</button>
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
  name:"Advertises",
  data(){
    return{
      imagePreviewUrl:null,
      preview:false,
      array:[],
      advertises:{},
      regions:{},
      loaded:false,
      sending:false,
      current_page:1,
      last_page:1,
      createModal:false,
      editModal:false,
      deleteModal:false,
      filterModal:false,
      formData:{
        title:'',
        type:1,
        status:'منزل',
        region_id:1,
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
      },
      advertise_images:null,
      editFormData:{},
      FilterFormData:{
        type: 0,
        status: ''
      },
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
    getRegions(){
      Axios.get('advertises/regions')
      .then(res => {
        this.regions = res.data;
      })
      .catch(err => {
        console.log(err)
      });
    },
    CreateAdvertise(){
      this.sending = true;
      Axios.post(`advertises/create` , this.formData)
      .then(res => {
        this.uploadAdvertiseFiles(res.data.advertise.id);
        this.getAdvertises(1);
        this.createModal = false;
        this.sending = false;
        this.formData = {
          title:'',
          type:1,
          status:'منزل',
          region_id:1,
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
        };
        this.successMessage = res.data.message;
        this.successSnackbar = true;
        // this.$refs.images.value=null;
      })
      .catch(err => {
        if(err.response.data.status == 'failed'){
          this.errorMessage = err.response.data.message;
          this.errorSnackbar = true;
          this.sending = false;
        }
      });
    },
    uploadAdvertiseFiles(advertise_id){
      if(this.advertise_images !== null){
        const data = new FormData();
        data.append('id', advertise_id);
        for(var i=0 ; i < this.advertise_images.length ; i++){
          let file = this.advertise_images[i];
          data.append('images['+ i +']', file);
        }
        Axios.post(`advertises/uploadFiles` , data,
        {
          headers: {
          'Content-Type': 'multipart/form-data'
          }
        })
        .then(res => {
          console.log(res.data)
          this.advertise_images = null;
        })
        .catch(err => {
          console.log(err.response)
          this.advertise_images = null;
        });
      }
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
      this.sending = true;
      Axios.post(`advertises/edit` , this.editFormData)
      .then(res => {
        this.array = [];
        this.uploadAdvertiseFiles(this.editFormData.id);
        this.getAdvertises(1);
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
    FilterAdvertise(){
      this.loaded = false;
      Axios.post('advertises/filter' , this.FilterFormData)
      .then(res => {
        this.advertises = res.data.data;
        this.last_page = res.data.last_page;
        this.loaded = true;
        this.array = [];
      })
      .catch(err => {
        console.log(err)
        this.loaded = true;
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
    this.getRegions();
    this.getAdvertises(this.current_page);
  }
}
</script>
<style>

</style>