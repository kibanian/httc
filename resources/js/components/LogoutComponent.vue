<template>
    <v-layout justify-center fluid wrap>
        <v-flex xs12>
            <p>Logout.</p>
        </v-flex>
    </v-layout>
</template>

<script>

import axios from 'axios'

export default {
    name: 'LogoutComponent',
    data: () => {
        return {

        }
    },
    mounted: function(){
        this.logout()
    },
    methods: {
        logout: function(){
            
            axios.post('/api/logout')
            .then((response) => {
                state.isLogin = false
                axios.defaults.headers.common['Authorization'] = null
                this.$router.push({ path: '/' })
            })
            .catch((e) => {
                console.log(e)
            })
        }
    },
}

</script>