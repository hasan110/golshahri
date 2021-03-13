import LoaderPannel from '../../components/LoaderPannel.vue';
import Dashboard from '../../components/Dashboard/Dashboard.vue';

import Users from '../../components/Users/Users.vue';
import Admins from '../../components/Users/Admins.vue';
import AdminProfile from '../../components/Users/AdminProfile.vue';
import Roles from '../../components/Users/Roles.vue';
import Permissions from '../../components/Users/Permissions.vue';

import SlideShows from '../../components/App/SlideShows.vue';
import Setting from '../../components/App/Setting.vue';

import Advertises from '../../components/Advertises/Advertises.vue';

import Businesses from '../../components/Businesses/Businesses.vue';

const allUrl = [
    {
        path: '/admin-area/Panel',
        component: LoaderPannel,
        redirect: '/admin-area/Panel/Dashboard',
        children: [
            { path: 'Dashboard', name: 'Dashboard', component: Dashboard },

            { path: 'Users', name: 'Users', component: Users },
            { path: 'Admins', name: 'Admins', component: Admins },
            { path: 'AdminProfile', name: 'AdminProfile', component: AdminProfile },
            { path: 'Roles', name: 'Roles', component: Roles },
            { path: 'Permissions', name: 'Permissions', component: Permissions },

            { path: 'Advertises', name: 'Advertises', component: Advertises },

            { path: 'Businesses', name: 'Businesses', component: Businesses },
            
            { path: 'SlideShows', name: 'SlideShows', component: SlideShows },
            { path: 'Setting', name: 'Setting', component: Setting },
        ]
    }
];
export default allUrl;