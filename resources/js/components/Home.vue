<template>
    <b-container class="text-center">
        <h1>Profile lists</h1>
        <b-table striped hover :items="profiles">
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(images)="data">
                <b-img v-if="data.item.images[0]" fluid :src="mainUrl+'/images/'+data.item.images[0]" alt="img"></b-img>
                <b-img v-else fluid :src="mainUrl+'/images/default.jpg'" alt="img"></b-img>
            </template>
            <template v-slot:cell(actions)="data">
                <router-link :to="{path: '/edit/' + data.value}" class="nav-link">
                    <b-icon icon="gear-wide-connected" aria-hidden="true"></b-icon>
                </router-link>
                <b-icon @click="deleteProfile(data.value)" icon="trash-fill" aria-hidden="true"></b-icon>
            </template>
        </b-table>
    </b-container>
</template>

<script>
    export default {
        mounted() {
            this.$store.dispatch('getProfiles');
        },
        computed: {
            profiles() {
                return this.$store.getters.profiles
            },
            mainUrl() {
                return this.$store.getters.mainUrl
            }
        },
        methods: {
            deleteProfile(data){
                if(!window.confirm('Are you sure you want to delete this profile?')){
                    return;
                }
                this.$store.dispatch('deleteProfile', data);
            }
        }
    }
</script>

<style scoped>
    .b-icon {
        width: 25px;
        height: 25px;
        color: red;
        margin-top: 10px;
        cursor: pointer;
    }
    .img-fluid {
        width: 50px;
        height: 50px;
    }
    .nav-link {
        display: inline;
    }
</style>