<template>
    <div class="">
        <div class="container" style="margin-top:60px">
            <div style="height:auto;" class="card m-b-0">
                <div class="card-header text-center">
                    آگهی های من
                </div>
                <div class="card-body">
                    <div v-if="businesses_count" class="row">
                        <div v-for="(business , key) in businesses" :key="key" class="p-0 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="card advertise-card">
                            <div class="row">
                                <div class="col-6">
                                <h6 class="mt-3 ml-1">{{ business.title }}</h6>
                                <p v-if="business.confirmed" class="m-2 badge badge-success">منتشر شد</p>
                                <p v-else class="m-2 badge badge-danger">بزودی منتشر میشود ...</p>
                                </div>
                                <div class="col-6 pr-0 pt-0">
                                    <div class="card-image-wrapper">
                                        <img class="card-image"  :src="ImageUrl+business.image" alt="">
                                    </div>
                                </div>
                            </div>
                            <router-link :to="{name:'EditBusiness' , params:{business_id:business.id}}" class="btn btn-primary btn-sm details-btn"><i class="fa fa-edit"></i></router-link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        در حال حاضر هیچ آگهی توسط شما ثبت نشده است .
                        <h1><i class="fa fa-info-circle"></i></h1>
                    </div>
                </div>
            </div>
        </div>

        <v-snackbar color="red" :timeout="4000" v-model="errorSnackbar">
            <div>{{errorMessage}}</div>
        </v-snackbar>
        
        <div v-if="loading" class="appLoading">
            <div class="circles-wrapper">
                <div></div><div></div><div></div><div></div>
            </div>
        </div>
    </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
import { mapActions } from 'vuex'
export default {
    name:"MyBusinesses",
    data(){
        return{
            errorSnackbar:false,
            errorMessage:'',
            businesses : {},
            loading : true,
            businesses_count:1,
            current_page:1,
            last_page:1,
        }
    },
    methods :{
        ...mapActions([
            'setRedirectRoute',
        ]),
        getMyBusinesses(page){
            const auth_token = localStorage.getItem('auth_token');
            Axios.get(`getUserBusinesses?auth_token=${auth_token}`)
            .then(res => {
                this.businesses = res.data.businesses.data;
                this.businesses_count = res.data.businesses_count;
                this.last_page = res.data.last_page;
                this.loading = false;
            })
            .catch(err => {
                console.log(err)
            });
        },
    },
    beforeMount(){
        if(localStorage.getItem('auth_token') === null){
            this.setRedirectRoute('/MyBusinesses');
            this.$router.push('/Login');
        }

        this.getMyBusinesses(this.current_page)
    }
}
</script>
<style scoped>

</style>