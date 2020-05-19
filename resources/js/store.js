import axios from "axios";

export default {
    state: {
        profiles: [],
        mainUrl: process.env.MIX_APP_URL
    },
    mutations: {
        updateProfiles(state, payload) {
            state.profiles = payload;
        }
    },
    getters: {
        profiles(state){
            return state.profiles;
        },
        mainUrl(state){
            return state.mainUrl;
        }
    },
    actions: {
        getProfiles(ctx) {
            axios.get('/api/profiles')
            .then((response) => {
                ctx.commit('updateProfiles', response.data.profiles);
            })
        },
        deleteProfile(ctx,data) {
            axios.post('/api/profiles/delete', {
                id : data
            })
            .then((response) => {
                ctx.commit('updateProfiles', response.data.profiles);
            })
        }
    }
}