<template>
    <v-layout justify-center fluid wrap>
        <v-content class="pa-0">
            <v-container>
                <header>
                    <v-layout row>
                        <div>
                            <p class="mb-0"><span class="red--text subheading">{{ japanese_formatted_month }}</span></p>
                            <p class="mb-0"><span class="title">{{ japanese_formatted_date }}</span></p>
                        </div>
                        <div>
                            <h1 class="title">{{ event.court_name }}</h1>
                            <p class="mb-0">主催者：{{ event.host_name }}</p>
                        </div>
                        
                    </v-layout>
                    <v-layout justify-space-around>
                        <template v-for="item in attend_selector">
                            <div @click="decide(item.status)">
                                <p class="text-xs-center mb-0"><v-icon large>{{ item.icon }}</v-icon></p>
                                <p class="text-xs-center mb-0">{{ item.text }}</p>
                            </div>
                        </template>
                    </v-layout>
                </header>
                <main>
                    <v-list>
                        <template v-for="item in base_info">
                            <v-list-tile>
                                <v-list-tile-action>
                                    <v-icon>{{ item.icon }}</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                                    <v-list-tile-sub-title v-if="item.subtitle">{{ item.subtitle }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-divider></v-divider>
                        </template>
                    </v-list>
                    <v-layout justify-space-around>
                        <div v-for="item in count_info">
                            <p class="title text-xs-center mb-0">{{ item.count }}</p>
                            <p class="text-xs-center mb-0">{{ item.text }}</p>
                        </div>
                    </v-layout>
                    <v-list v-for="item in attend_info">
                        <v-subheader>
                            <span class="title">{{ item.text }}</span>
                        </v-subheader>
                        <template v-if="item.members.length">
                            <template v-for="member in item.members">
                                <v-list-tile>
                                    <v-list-tile-content>
                                        <v-list-tile-title>#氏名# {{ member.card_count }}枚</v-list-tile-title>
                                        <v-list-tile-sub-title>{{ member.created_at }}</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-divider></v-divider>
                            </template>
                        </template>
                        <template v-else>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <p>-</p>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>
                    </v-list>
                </main>
            </v-container>
        </v-content>
    </v-layout>
</template>

<script>

import axios from 'axios'

export default {
    name: 'EventDetailComponent',
    data: () => {
        return {
            event: {},
            login_user_id: null,
            attend_selector: [
                { icon: 'check', text: '参加', status: 1 },
                { icon: 'cancel', text: '不参加', status: 4 }
            ],
            text_approved: '参加予定',
            text_waiting: 'キャンセル待ち',
            text_canceled: 'キャンセル済',
        }
    },
    mounted: function(){
        this.get_event()
    },
    computed: {
        objected_start_date: function(){
            return new Date(this.event.start_date)
        },
        japanese_formatted_month: function(){
            const month = this.objected_start_date.getMonth() + 1
            return month+'月'
        },
        japanese_formatted_date: function(){
            const date = this.objected_start_date.getDate()
            return date+'日'
        },
        approved_count: function(){
            return this.event.approved ? this.event.approved.length : 0
        },
        waiting_count: function(){
            return this.event.waiting ? this.event.waiting.length : 0
        },
        canceled_count: function(){
            return this.event.canceled ? this.event.canceled.length : 0
        },
        schedule_title: function(){
            return this.japanese_formatted_month+this.japanese_formatted_date+this.event.start_time+'～'+this.event.end_time
        },
        base_info: function(){
            return [
                { icon: 'schedule', title: this.schedule_title, subtitle: '3週間後' },
                { icon: 'place', title: this.event.court_name, subtitle: '〒'+this.event.address },
                { icon: 'attach_money', title: 'コート代/人数+300円', subtitle: null },
                { icon: 'group', title: this.event.member_limit+'人まで', subtitle: null },
                { icon: 'layers', title: this.event.court_number+'面', subtitle: null }
            ]
        },
        count_info: function(){
            return [
                { count: this.approved_count, text: this.text_approved },
                { count: this.waiting_count, text: this.text_waiting },
                { count: this.canceled_count, text: this.text_canceled }
            ]
        },
        attend_info: function(){
            return [
                { text: this.text_approved, members: this.event.approved },
                { text: this.text_waiting, members: this.event.waiting },
                { text: this.text_canceled, members: this.event.canceled },
            ]
        },
        //参加していないイベントをキャンセルしようとしている
        is_cancel_of_not_attended_event: function(){
            return false
        },
        //参加予定のイベントに再度参加表明する
        is_attend_again_of_attended_event: function(){
            return false
        }
    },
    methods: {
        get_event: function(){
            let data = {}
            axios.get('/api/event/'+this.$route.params.id)
            .then((response) => {
                data = response.data

                if(data.result == 'success'){
                    this.event = data.event
                    this.login_user_id = data.login_user_id

                }
            })
            .catch(e => {
                console.log(e)
            })
        },
        decide: function(status){
            if(this.is_cancel_of_not_attended_event){
                alert('参加していないイベントはキャンセルできません。')
                return
            }

            if(this.is_attend_again_of_attended_event){
                alert('すでに参加予定のイベントです。')
                return
            }

            let params = new URLSearchParams()
            params.append('event_id', this.$route.params.id)
            params.append('user_id', this.login_user_id)
            params.append('status', status)
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
    }

}

</script>