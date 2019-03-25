<template>
    <v-content>
        <v-container fluid fill-height>
            <v-layout justify-center fluid wrap>
                <v-flex xs12>
                    <v-form
                        ref="form"
                    >
                        <v-dialog
                            ref="dialog"
                            v-model="modal"
                            :return-value.sync="event_start_date"
                            persistent
                            lazy
                            full-width
                            width="290px"
                            >
                            <v-text-field
                                slot="activator"
                                v-model="event_start_date"
                                label="開催日"
                                prepend-icon="event"
                                readonly
                            ></v-text-field>
                            <v-date-picker v-model="event_start_date" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="modal = false">Cancel</v-btn>
                                <v-btn flat color="primary" @click="$refs.dialog.save(event_start_date)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                        <v-select
                            v-model="event_start_time"
                            :rules="startTimeRules"
                            :items="times"
                            label="開始時刻"
                        ></v-select>
                        <v-select
                            v-model="event_end_time"
                            :rules="endTimeRules"
                            :items="times"
                            label="終了時刻"
                        ></v-select>
                        <v-select
                            v-model="court_id"
                            :items="courts"
                            item-text="court_name"
                            item-value="court_id"
                            :rules="courtIdRules"
                            label="テニスコート"
                        ></v-select>
                        <v-select
                            v-model="court_number"
                            :rules="courtNumberRules"
                            :items="numbers"
                            label="面数"
                        ></v-select>
                        <v-text-field
                            v-model="member_limit"
                            label="参加人数の上限"
                            readonly
                        ></v-text-field>
                        <v-select
                            v-model="fee_type"
                            :rules="feeTypeRules"
                            :items="fee_types"
                            item-text="fee_type_name"
                            item-value="fee_type"
                            label="参加費の計算方法"
                        ></v-select>
                        <v-select
                            v-model="newcomer_count"
                            :items="newcomers"
                            label="新規参加者の数"
                        ></v-select>
                        <v-select
                            v-model="status"
                            :items="status_list"
                            item-text="status_name"
                            item-value="status"
                            label="開催/中止"
                        ></v-select>
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
            event_start_time: null,
            event_end_time: null,
            court_id: null,
            court_number: null,
            fee_type: null,
            newcomer_count: 0,
            host_id: null,
            status: 1,
            event_start_date: new Date().toISOString().substr(0, 10),
            
            menu: false,
            modal: false,
            menu2: false,
            times: [
                '17:00',
                '17:30',
                '18:00',
                '18:30',
                '19:00',
                '19:30',
                '20:00',
                '20:30',
                '21:00'
            ],

            court_id: null,
            courts: [
                { 'court_id': 1, 'court_name': '日比谷公園テニスコート' },
                { 'court_id': 2, 'court_name': '木場公園テニスコート' },
            ],

            numbers: [],

            newcomers: [],

            fee_types: [
                { 'fee_type': 1, 'fee_type_name': '(コート代/参加人数)+300円' }
            ],

            status_list: [
                { 'status': 1, 'status_name': '開催' },
                { 'status': 2, 'status_name': '中止' },
            ],

            startTimeRules: [
                v => !!v || '開始時刻を入力してください。'
            ],
            endTimeRules: [
                v => !!v || '終了時刻を入力してください。'
            ],

            courtIdRules: [
                v => !!v || 'テニスコートを選択してください。'
            ],

            courtNumberRules: [
                v => !!v || '面数を選択してください。'
            ],

            feeTypeRules: [
                v => !!v || '参加費の計算方法を選択してください。'
            ],
        }
    },
    computed: {
        //基本的に1面あたり8人まで
        member_limit: function(){
            return 8 * (parseInt(this.court_number) || 1)
        },
        start_datetime: function(){
            return (this.event_start_date && this.event_start_time)
            ? new Date(this.event_start_date+' '+this.event_start_time)
            : null
        },
        end_datetime: function(){
            return (this.event_start_date && this.event_end_time)
            ? new Date(this.event_start_date+' '+this.event_end_time)
            : null
        },
        splitted_start_date: function(){
            return this.event_start_date
            ? this.event_start_date.split('-')
            : []
        },
        splitted_start_time: function(){
            return this.event_start_time
            ? this.event_start_time.split(':')
            : []
        },
        splitted_end_date: function(){
            return this.event_start_date
            ? this.event_start_date.split('-')
            : []
        },
        splitted_end_time: function(){
            return this.event_end_time
            ? this.event_end_time.split(':')
            : []
        },
        start_year: function(){
            return this.splitted_start_date != []
            ? this.splitted_start_date[0]
            : null
        },
        start_month: function(){
            return this.splitted_start_date != []
            ? this.splitted_start_date[1]
            : null
        },
        start_date: function(){
            return this.splitted_start_date != []
            ? this.splitted_start_date[2]
            : null
        },
        start_hour: function(){
            return this.splitted_start_time != []
            ? this.splitted_start_time[0]
            : null
        },
        start_minute: function(){
            return this.splitted_start_time != []
            ? this.splitted_start_time[1]
            : null
        },
        end_year: function(){
            return this.splitted_end_date != []
            ? this.splitted_end_date[0]
            : null
        },
        end_month: function(){
            return this.splitted_end_date != []
            ? this.splitted_end_date[1]
            : null
        },
        end_date: function(){
            return this.splitted_end_date != []
            ? this.splitted_end_date[2]
            : null
        },
        end_hour: function(){
            return this.splitted_end_time != []
            ? this.splitted_end_time[0]
            : null
        },
        end_minute: function(){
            return this.splitted_end_time != []
            ? this.splitted_end_time[1]
            : null
        },
    },
    mounted: function(){
        this.get_host_id()
        for(let i = 1;i <= 6;i++){
            this.numbers.push(i)
        }
        for(let j = 0;j <= 4;j++){
            this.newcomers.push(j)
        }
    },
    methods: {
        submit: function(){

            if(!this.$refs.form.validate()){
                return
            }

            const today = new Date()

            if(today > this.start_datetime){
                alert('練習の開始日には本日以降の日付を選択してください。')
                return
            }

            if(this.start_datetime > this.end_datetime){
                alert('練習の終了時刻は開始時刻より後の時刻を選択してください。')
                return
            }

            let params = new URLSearchParams()
            params.append('start_year', this.start_year)
            params.append('start_month', this.start_month)
            params.append('start_date', this.start_date)
            params.append('start_hour', this.start_hour)
            params.append('start_minute', this.start_minute)
            params.append('end_year', this.end_year)
            params.append('end_month', this.end_month)
            params.append('end_date', this.end_date)
            params.append('end_hour', this.end_hour)
            params.append('end_minute', this.end_minute)
            params.append('court_id', this.court_id)
            params.append('member_limit', this.member_limit)
            params.append('court_number', this.court_number)
            params.append('fee_type', this.fee_type)
            params.append('newcomer_count', this.newcomer_count)
            params.append('host_id', this.host_id)
            params.append('status', this.status)
            axios.post('/api/event', params)
            .then((response) => {
                let data = response.data
                let status = response.status
                if(data == 'failed' || status != '200'){
                    alert('予期せぬエラーが発生しました。')
                }else{
                    alert('イベントの登録が完了しました。')
                }
            })
            .catch((e) => {
                console.log(e)
            })
        },
        get_host_id: function(){
            //php側でセッションから取得するようにする
            this.host_id = 1
        }
    }
}

</script>