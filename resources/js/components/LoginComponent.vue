<template>
    <v-layout justify-center fluid wrap>
        <v-flex xs12>
            <v-form
                ref="form"
            >
                <v-text-field
                    v-model="email"
                    label="メールアドレス"
                    :rules="emailRule"
                ></v-text-field>
                <v-text-field
                    v-model="password"
                    label="パスワード"
                    :rules="passwordRule"
                    :type="'password'"
                ></v-text-field>
                <v-btn @click="submit()">ログイン</v-btn>
            </v-form>
        </v-flex>
    </v-layout>
</template>

<script>

import axios from 'axios'

export default {
    name: 'LoginComponent',
    data: () => {
        return {
            email: null,
            emailRule: [
                v => !!v || 'メールアドレスを入力してください。',
                v => /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(v) || 'メールアドレスを正しい形式で入力してください。'
            ],
            password: null,
            passwordRule: [
                v => !!v || 'パスワードを入力してください。',
                v => /^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,16}$/.test(v) || 'パスワードは半角英数字をそれぞれ1種類以上含む8文字以上16文字で入力してください。'
            ]
        }
    },
    computed: {
        
    },
    mounted: function(){
    },
    methods: {
        submit: function(){
            if(!this.$refs.form.validate()){
                return
            }

            let params = new URLSearchParams()
            params.append('email', this.email)
            params.append('password', this.password)
            axios.post('/api/login', params)
            .then((response) => {
                const data = response.data
                console.log(data)
                if(data.result == 'success'){
                    const token = response.data.access_token;
                    axios.defaults.headers.common['Authorization'] = 'Bearer '+token
                    state.is_login = true
                    this.$router.push({ path: '/' })
                }else{
                    alert('ログイン失敗。')
                }
                
            })
            .catch((e) => {
                console.log(e)
            })
        }
    },
}

</script>