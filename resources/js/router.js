import VueRouter from 'vue-router';
import Home from './components/Home';
import NewProfile from './components/NewProfile';
import Profile from './components/Profile';

export default new VueRouter({
    routes: [
        {
            path: '/',
            component: Home
        },
        {
            path: '/create-profile',
            component: NewProfile
        },
        {
            path: '/edit/:id',
            component: Profile
        },

    ],
    mode: 'history'
});