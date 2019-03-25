<template>
    <v-content>
        <v-container fluid fill-height>
            <v-layout justify-center fluid wrap>
                <v-flex xs12>
                    <v-form
                        ref="form"
                    >
                        <v-text-field
                            v-model="court_name"
                            :rules="courtNameRules"
                            counter
                            maxlength="20"
                            label="テニスコート名"
                        ></v-text-field>
                        <v-text-field
                            v-model="zipcode"
                            :rules="zipCodeRules"
                            label="郵便番号"
                            counter
                            maxlength="8"
                        ></v-text-field>
                        <v-text-field
                            v-model="address"
                            :rules="addressRules"
                            label="住所"
                            counter
                            maxlength="30"
                        ></v-text-field>
                        <v-btn @click="submit()">登録</v-btn>
                    </v-form>
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
</template>

<script>

import axios from 'axios'

export default {
    name: 'EventRegistComponent',
    data: () => {
        return {
            court_name: null,
            courtNameRules: [
                v => !!v || 'テニスコート名を入力してください。',
                v => (v && v.length <= 30) || 'テニスコート名は30文字以内で入力してください。'
            ],
            zipcode: null,
            zipCodeRules: [
                v => !!v || '郵便番号を入力してください。',
                v => /^\d{3}[-]\d{4}$/.test(v) || '郵便番号はハイフンありの7桁で入力してください。'
            ],
            address: null,
            addressRules: [
                v => !!v || '住所を入力してください。',
                v => (v && v.length <= 30) || '住所は30文字以内で入力してください。'
            ]
        }
    },
    methods: {
        submit: function(){
            if(this.$refs.form.validate()){
                let params = new URLSearchParams()
                params.append('court_name', this.court_name)
                params.append('zipcode', this.zipcode)
                params.append('address', this.address)
                axios.post('/api/tenniscourt', params)
                .then((response) => {
                    console.log(response)
                })
                .catch((e) => {
                    console.log(e)
                })
            }
        }
    }
}

</script>