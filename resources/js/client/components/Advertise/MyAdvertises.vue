<template>
    <div class="">
        <div class="container" style="margin-top:60px">
            <div style="height:auto;" class="card m-b-0">
                <div class="card-header text-center">
                    آگهی های املاک من
                </div>
                <div class="card-body">
                    <div v-if="advertises_count" class="row">
                        <div v-for="(advertise , key) in advertises" :key="key" class="p-0 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            <div class="card advertise-card">
                            <div class="row">
                                <div class="col-6">
                                <h6 class="mt-3 ml-1">{{ advertise.title }}</h6>
                                <small class="m-2 small">محدوده {{ advertise.street }}</small>
                                <small class="m-2 small">متراژ : {{ advertise.area }} متر</small>
                                <p v-if="advertise.confirmed" class="m-2 badge badge-success">منتشر شد</p>
                                <p v-else class="m-2 badge badge-danger">بزودی منتشر میشود ...</p>
                                </div>
                                <div class="col-6 pr-0 pt-0">
                                    <div class="card-image-wrapper">
                                        <img class="card-image"  :src="ImageUrl+advertise.image" alt="">
                                    </div>
                                </div>
                            </div>
                            <router-link :to="{name:'EditAdvertise' , params:{advertise_id:advertise.id}}" class="btn btn-primary btn-sm details-btn"><i class="fa fa-edit"></i></router-link>
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
    name:"MyAdvertises",
    data(){
        return{
            errorSnackbar:false,
            errorMessage:'',
            advertises : {},
            loading : true,
            advertises_count:1,
            current_page:1,
            last_page:1,
        }
    },
    methods :{
        ...mapActions([
            'setRedirectRoute',
        ]),
        getMyAdvertises(page){
            const auth_token = localStorage.getItem('auth_token');
            Axios.get(`getUserAdvertises?auth_token=${auth_token}`)
            .then(res => {
                this.advertises = res.data.advertises.data;
                this.advertises_count = res.data.advertises_count;
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
            this.setRedirectRoute('/MyAdvertises');
            this.$router.push('/Login');
        }

        this.getMyAdvertises(this.current_page)
    }
}
</script>
<style scoped>

</style>