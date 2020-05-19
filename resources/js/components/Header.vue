<template>
    <div id="header">
        <b-navbar type="dark" variant="dark">
            <b-navbar-nav>
                <b-nav-item>
                    <router-link to="/" class="nav-link">Profile lists</router-link>
                </b-nav-item>
                <b-nav-item>
                    <router-link to="/create-profile" class="nav-link">Create profile</router-link>
                </b-nav-item>
            </b-navbar-nav>
        </b-navbar>
    </div>
</template>

<script>
    export default {
        name: 'Header'
    }
</script>  