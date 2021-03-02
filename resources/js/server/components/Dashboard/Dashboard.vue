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
                                    <button type="button" class="btn btn-transparent active dropdown-toggle p-a-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-settings"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
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
                                    <i class="icon-location-pin"></i>
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
                                    <button type="button" class="btn btn-transparent active dropdown-toggle p-a-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-settings"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
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
                                    <button type="button" class="btn btn-transparent active dropdown-toggle p-a-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-settings"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                                <h4 class="m-b-0">9.823</h4>
                                <p>کاربر آنلاین</p>
                            </div>
                        </div>
                    </div>
                    <!--/col-->

                </div>
                <!--/row-->
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
        }
    },
    methods:{
        getDashboardDetails(){
            Axios.get(`getDashboardDetails`)
            .then(res => {
                this.usersCount = res.data.usersCount;
                this.confirmedAdvertisesCount = res.data.confirmedAdvertisesCount;
                this.advertisesCount = res.data.advertisesCount;
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