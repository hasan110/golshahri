<template>
    <div class="">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">داشبورد</li>
        </ol>

        <div style="height:80vh;overflow:auto;" class="container-fluid p-x-3">

            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-inverse card-primary">
                            <div class="card-block p-b-0">
                                <div class="btn-group pull-left">
                                    <button type="button" class="btn btn-transparent active p-a-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-user"></i>
                                    </button>
                                </div>
                                <h4 class="m-b-0">{{usersCount}}</h4>
                                <p>تعداد کاربران</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-inverse card-info">
                            <div class="card-block p-b-0">
                                <button type="button" class="btn btn-transparent active p-a-0 pull-left">
                                    <i class="icon-layers"></i>
                                </button>
                                <h4 class="m-b-0">{{ advertisesCount }}</h4>
                                <p>تعداد آگهی ها</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-inverse card-warning">
                            <div class="card-block p-b-0">
                                <div class="btn-group pull-left">
                                    <button type="button" class="btn btn-transparent active p-a-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-info"></i>
                                    </button>
                                    <!-- <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div> -->
                                </div>
                                <h4 class="m-b-0">{{ confirmedAdvertisesCount }}</h4>
                                <p>آگهی های تایید نشده</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-->

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-inverse card-danger">
                            <div class="card-block p-b-0">
                                <div class="btn-group pull-left">
                                    <button type="button" class="btn btn-transparent active p-a-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-settings"></i>
                                    </button>
                                </div>
                                <h4 class="m-b-0">{{ todayViews }}</h4>
                                <p>کل بازدید های امروز</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-->

                </div>
                <!--/row-->

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="social-box facebook">
                            <i class="fa fa-eye"></i>
                            <ul>
                                <li>
                                    <strong>{{ allViewsFromHome }}</strong>
                                    <span>همه بازدید ها از سایت</span>
                                </li>
                                <li>
                                    <strong>{{ allViewsFromAds }}</strong>
                                    <span>همه بازدید ها از آگهی</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="social-box twitter">
                            <i class="fa fa-eye-slash"></i>
                            <ul>
                                <li>
                                    <strong>{{ todayViewsFromHome }}</strong>
                                    <span>بازدید های امروز از سایت</span>
                                </li>
                                <li>
                                    <strong>{{ todayViewsFromAds }}</strong>
                                    <span>بازدید های امروز از آگهی</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--/.container-fluid-->
    </div>
</template>
<script>
import Axios from '../../Services/httpRequest/axios';
export default {
    name:"Dashboard",
    data(){
        return {
            usersCount:'',
            confirmedAdvertisesCount:'',
            advertisesCount:'',
            todayViews:'',
            todayViewsFromHome:'',
            todayViewsFromAds:'',
            allViewsFromHome:'',
            allViewsFromAds:'',
        }
    },
    methods:{
        getDashboardDetails(){
            Axios.get(`getDashboardDetails`)
            .then(res => {
                this.usersCount = res.data.usersCount;
                this.confirmedAdvertisesCount = res.data.confirmedAdvertisesCount;
                this.advertisesCount = res.data.advertisesCount;
                this.todayViews = res.data.todayViews;
                this.todayViewsFromHome = res.data.todayViewsFromHome;
                this.todayViewsFromAds = res.data.todayViewsFromAds;
                this.allViewsFromHome = res.data.allViewsFromHome;
                this.allViewsFromAds = res.data.allViewsFromAds;
            })
            .catch(err => {
                console.log(err)
            });
        }
    },
    beforeMount(){
        this.getDashboardDetails();
    }
}
</script>
<style scoped>

</style>