<template>
    <v-layout justify-center fluid wrap>
        <v-flex xs12>
            <v-form
                ref="form"
            >
                <v-text-field
                    v-model="name"
                    label="名前"
                    :counter="20"
                    :rules="nameRule"
                ></v-text-field>
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
                <v-btn @click="submit()">登録</v-btn>
            </v-form>
        </v-flex>
    </v-layout>
</template>

<script>

import axios from 'axios'

export default {
    name: 'UserRegistComponent',
    data: () => {
        return {
            name: null,
            nameRule: [
                v => !!v || '名前を入力してください。',
                v => (v && v.length <= 20) || '名前は20字以内で入力してください。'
            ],
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
            params.append('name', this.name)
            params.append('email', this.email)
            params.append('password', this.password)
            axios.post('/api/user', params)
            .then((response) => {
                let data = response.data
                let status = response.status
                console.log(data)
                const msg = (data[0] == 'success' && status == '200')
                ? 'ユーザーの登録が完了しました。'
                : '予期せぬエラーが発生しました。' 
                alert(msg)
            })
            .catch((e) => {
                console.log(e)
            })
        }
    },
}

</script>