<template>
    <div class="">
        <div class="container" style="margin-top:50px">
            <div class="row">
                <div class="col-8 py-0">
                    <div class="operations">
                        <input v-model="search_key" @input="searchAdvertise" type="text" class="search-item" placeholder="جستجو...">
                    </div>
                </div>
                <div class="col-4 pt-1">
                    <div style="float:left;">
                        <a :href="setting.instagram_address"><img width="30px" height="30px" :src="ImageUrl+setting.instagram_image" alt=""></a>
                        <a :href="setting.telegram_address"><img width="30px" height="30px" :src="ImageUrl+setting.telegram_image" alt=""></a>
                    </div>
                </div>
            </div>

            <div class="row p-2">
                <div v-for="(advertise , key) in advertises" :key="key" class="advertise-wrapper p-0 col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <router-link :to="{name:'Advertise' , params:{advertise_id:advertise.id}}" class="advertise-link">
                    <div class="card advertise-card">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="advertise-title mt-1 ml-1 nowrap">{{ advertise.title }}</h6>
                            <small class="m-2 small nowrap">محدوده {{ advertise.street }}</small>
                            <small v-if="advertise.area" class="m-2 small">متراژ : {{ advertise.area }} متر</small>
                            <p v-if="advertise.type == 'فروش'" class="m-2 badge badge-success">{{ advertise.type }} {{ advertise.status }}</p>
                            <p v-else class="m-2 badge badge-danger">{{ advertise.type }} {{ advertise.status }}</p>
                        </div>
                        <div class="col-6 pr-0 pt-0">
                            <div class="card-image-wrapper">
                                <img class="card-image"  :src="ImageUrl+advertise.image" alt="">
                            </div>
                        </div>
                    </div>
                    <router-link :to="{name:'Advertise' , params:{advertise_id:advertise.id}}" class="btn btn-legendary btn-sm details-btn">اطلاعات بیشتر...</router-link>
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
    name:"Home",
    data(){
        return {
            advertises : [],
            loading : true,
            current_page:1,
            last_page:1,
            setting:{},   
            search_key: ''
        }
    },
    methods:{
        getAdvertises(page){
            Axios.get(`advertises/list?page=${page}`)
            .then(res => {
                this.advertises = this.advertises.concat(res.data.data)
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
        searchAdvertise(){
            const key = this.search_key
            if(key === ''){
                this.advertises = [];
                this.getAdvertises(1);
            }else{
                Axios.post('advertises/search' , {key})
                .then(res => {
                    this.advertises = res.data;
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
        this.getAdvertises(page);
        }
    },
    beforeMount(){
        this.getAdvertises(this.current_page);
        this.getSettings();
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