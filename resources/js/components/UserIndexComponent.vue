<template>
    <v-layout justify-center fluid wrap>
        <v-data-table
            :headers="headers"
            :items="users"
        >
            <template slot="headers" slot-scope="props">
                <tr v-for="header in props.headers">
                    <th>{{ header.id }}</th>
                    <th>{{ header.user_id }}</th>
                    <th>{{ header.name }}</th>
                    <th>{{ header.email }}</th>
                    <th>{{ header.cards }}</th>
                    <th>{{ header.add_card }}</th>
                </tr>
            </template>
            <template slot="items" slot-scope="props">
                <td class="text-xs-center">{{ props.item.id }}</td>
                <td class="text-xs-left">{{ props.item.user_id }}</td>
                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-left">{{ props.item.email }}</td>
                <td class="text-xs-center">{{ props.item.cards.length }}</td>
                <td class="text-xs-center"><v-btn @click="open_card_regist_dialog(props.item.user_id, props.item.name)">登録</v-btn></td>
            </template>
        </v-data-table>
        <card-regist-component
            ref="card_regist"
            v-bind:user_id="current_user_id"
            v-bind:user_name="current_name"
        ></card-regist-component>
    </v-layout>
</template>

<script>

import axios from 'axios'
import CardRegistComponent from './CardRegistComponent'

export default {
    name: 'UserIndexComponent',
    components: {
        CardRegistComponent
    },
    data: () => {
        return {
            users: [],
            headers: [{
                id: 'No.',
                user_id: 'ユーザーID',
                name: '名前',
                email: 'メールアドレス',
                cards: 'カード枚数',
                add_card: 'カード登録'
            }],
            current_user_id: null,
            current_name: null
        }
    },
    mounted: function(){
        this.get_users()
    },
    methods: {
        get_users: function(){
            let data = {}
            axios.get('/api/user')
            .then((response) => {
                data = response.data

                if(data.result == 'success'){
                    this.users = data.users
                }
            })
            .catch(e => {
                console.log(e)
            })
        },
        open_card_regist_dialog: function(current_user_id, current_name){
            this.current_user_id = current_user_id
            this.current_name = current_name
            this.$refs.card_regist.open()
        }
    }

}

</script>