<template>
    <v-layout justify-center fluid wrap>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">カード登録</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout column>
                            <v-form
                                ref="form"
                            >
                                <v-flex xs12>
                                    <v-text-field
                                        v-model="user_id"
                                        v-show=false
                                    ></v-text-field>
                                    <v-text-field
                                        v-model="name"
                                        label="ユーザー名"
                                        :rules="nameRules"
                                        readonly
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-select
                                        v-model="card_type"
                                        :items="card_types"
                                        item-text="text"
                                        item-value="type"
                                        :rules="cardTypeRules"
                                        label="カード種別"
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12>
                                    <p>カード登録年月</p>
                                    <v-date-picker
                                        v-model="picker"
                                        type="month"
                                        locale="ja-jp"
                                        full-width
                                        landscape
                                    ></v-date-picker>
                                </v-flex>
                            </v-form>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="close()">閉じる</v-btn>
                    <v-btn @click="submit()">登録</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>

import axios from 'axios'

export default {
    name: 'CardRegistComponent',
    props: [
        'user_id',
        'user_name'
    ],
    data: () => {
        return {
            dialog: false,
            nameRules: [
                v => !!v || '名前を入力してください。',
            ],
            card_type: null,
            card_types: [
                { type: 1, text: '都営' },
                { type: 2, text: '墨田区営' }
            ],
            cardTypeRules: [
                v => !!v || 'カード種別を選択してください。'
            ],
            picker: new Date().toISOString().substr(0, 7)
        }
    },
    computed: {
        name: function(){
            return this.user_name
        },
        made_datetime: function(){
            return this.picker.split('-')
        },
        made_year: function(){
            return this.made_datetime ? this.made_datetime[0] : null
        },
        made_month: function(){
            return this.made_datetime ? this.made_datetime[1] : null
        },
        made_date: function(){
            return 1
        }
    },
    mounted: function(){
    },
    methods: {
        open : function(){
            this.dialog = true
        },
        close: function(){
            this.dialog = false
        },
        submit: function(){
            if(!this.$refs.form.validate()){
                return
            }

            let params = new URLSearchParams()
            params.append('user_id', this.user_id)
            params.append('card_type', this.card_type)
            params.append('made_year', this.made_year)
            params.append('made_month', this.made_month)
            params.append('made_date', this.made_date)
            axios.post('/api/card', params)
            .then((response) => {
                let data = response.data
                let status = response.status
                console.log(data)
                const msg = (data == 'success' && status == '200')
                ? 'カードの登録が完了しました。'
                : '予期せぬエラーが発生しました。' 
                alert(msg)
                this.close()
            })
            .catch((e) => {
                console.log(e)
            })
        }
    },
}

</script>