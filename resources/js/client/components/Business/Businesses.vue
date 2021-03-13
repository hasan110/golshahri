<template>
    <div class="">
        <div class="container" style="margin-top:50px">
            <div class="row">
                <div class="col-8 py-0">
                    <div class="operations">
                        <input v-model="search_key" @input="searchBusiness" type="text" class="search-item" placeholder="جستجو...">
                    </div>
                </div>
                <div class="col-4 py-1">
                    <router-link to="/CreateBusiness" class="btn btn-legendary btn-sm float-left"> ثبت آگهی</router-link>
                </div>
            </div>

            <div class="row p-2">
                <div v-for="(business , key) in businesses" :key="key" class="advertise-wrapper p-0 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <router-link :to="{name:'Business' , params:{business_id:business.id}}" class="advertise-link">
                    <div class="card advertise-card">
                        <div class="row">
                            <div class="col-6">
                                <h6  style="font-size:14px" class="advertise-title mt-1 ml-1">{{ business.title }}</h6>
                                <hr>
                                <small class="m-2 small nowrap" style="font-size:12px"><i class="fa fa-eye" style="color:#444"></i> بازدید {{ business.view_count }} نفر</small>
                                <small class="m-2 small nowrap" style="font-size:12px"><i class="fa fa-hourglass-end" style="color:#444"></i>
                                <template v-if="business.days_ago">{{ business.days_ago }} روز قبل</template>
                                <template v-else>لحظاتی پیش</template>
                                </small>
                            </div>
                            <div class="col-6 pr-0 pt-0">
                                <div class="card-image-wrapper">
                                    <img class="card-image"  :src="ImageUrl+business.image" alt="">
                                </div>
                            </div>
                        </div>
                        <span class="btn btn-success btn-sm details-btn">{{business.category.title}}</span>
                    </div>
                    </router-link>
                </div>
            </div>
        </div>
        <div v-if="loading" class="appLoading">
            <div class="circles-wrapper">
                <div></div><div></div><div></div><div></div>
            </div>
        </div>
    </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
    name:"Businesses",
    data(){
        return {
            businesses : [],
            loading : true,
            current_page:1,
            last_page:1,
            setting:{},   
            search_key: ''
        }
    },
    methods:{
        getBusinesses(page){
            Axios.get(`businesses/list?page=${page}`)
            .then(res => {
                this.businesses = this.businesses.concat(res.data.data)
                this.last_page = res.data.last_page;
                this.loading = false;
            })
            .catch(err => {
                console.log(err)
            });
        },
        getSettings(){
            Axios.get(`getSettings`)
            .then(res => {
                this.setting = res.data;
            })
            .catch(err => {
                console.log(err)
            });
        },
        searchBusiness(){
            const key = this.search_key
            if(key === ''){
                this.businesses = [];
                this.getBusinesses(1);
            }else{
                Axios.post('businesses/search' , {key})
                .then(res => {
                    this.businesses = res.data;
                    this.last_page = 1;
                    this.current_page = 1;
                })
                .catch(err => {
                    console.log(err)
                });
            }
        },
    },
    watch:{
        current_page: function (page) {
        this.getBusinesses(page);
        }
    },
    beforeMount(){
        this.getSettings();
        this.getBusinesses(this.current_page);
    },
    created(){
        window.onscroll = () => {
            if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
                if(this.current_page < this.last_page){
                    this.current_page++;
                }
            }
        }
    }
}
</script>
<style scoped>

</style>