import LoaderPannel from '../../components/LoaderPannel.vue';
import Home from '../../components/Home/Home.vue';
import AboutUs from '../../components/Home/AboutUs.vue';

import Login from '../../components/Auth/Login.vue';
import Register from '../../components/Auth/Register.vue';
import Alert from '../../components/Auth/Alert.vue';
import GetCode from '../../components/Auth/GetCode.vue';

import CreateAdvertise from '../../components/Advertise/CreateAdvertise.vue';
import MyAdvertises from '../../components/Advertise/MyAdvertises.vue';
import EditAdvertise from '../../components/Advertise/EditAdvertise.vue';
import Advertise from '../../components/Advertise/Advertise.vue';
import Advertises from '../../components/Advertise/Advertises.vue';

import Business from '../../components/Business/Business.vue';
import Businesses from '../../components/Business/Businesses.vue';
import CreateBusiness from '../../components/Business/CreateBusiness.vue';
import MyBusinesses from '../../components/Business/MyBusinesses.vue';
import EditBusiness from '../../components/Business/EditBusiness.vue';

import NotFound from '../../components/MyComponents/NotFound.vue';

const allUrl = [
  {
    path: '/',
    component: LoaderPannel,
    redirect: '/Home',
    children: [
      { path: 'Home', name: 'Home', component: Home },
      { path: 'AboutUs', name: 'AboutUs', component: AboutUs },

      { path: 'Login', name: 'Login', component: Login },
      { path: 'Register', name: 'Register', component: Register },
      { path: 'GetCode', name: 'GetCode', component: GetCode },
      { path: 'Alert', name: 'Alert', component: Alert },

      { path: 'Advertises', name: 'Advertises', component: Advertises },
      { path: 'CreateAdvertise', name: 'CreateAdvertise', component: CreateAdvertise },
      { path: 'MyAdvertises', name: 'MyAdvertises', component: MyAdvertises },
      { path: 'EditAdvertise/:advertise_id', name: 'EditAdvertise', component: EditAdvertise },
      { path: 'Advertise/:advertise_id', name: 'Advertise', component: Advertise },

      { path: 'Businesses', name: 'Businesses', component: Businesses },
      { path: 'CreateBusiness', name: 'CreateBusiness', component: CreateBusiness },
      { path: 'MyBusinesses', name: 'MyBusinesses', component: MyBusinesses },
      { path: 'EditBusiness/:business_id', name: 'EditBusiness', component: EditBusiness },
      { path: 'Business/:business_id', name: 'Business', component: Business },

    ]
  },
  { path: '*', name: 'NotFound', component: NotFound },
];
export default allUrl;