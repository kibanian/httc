<template>
    <v-layout justify-center fluid wrap>
        <v-flex xs12>
            <template v-if="events.length">
                <v-card v-for="event in events">
                    <v-img
                        class="white--text"
                        height="200px"
                        src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
                    >
                        <v-container fill-height fluid>
                            <v-layout fill-height>
                                <v-flex xs12 align-end flexbox>
                                    <span class="headline">{{ event.start_date }}(水) {{ event.start_time }} {{ event.court_name }}</span>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-img>
                    <v-card-title>
                        <div>
                            <span class="grey--text">{{ event.start_date }}(水) {{ event.start_time }}~{{ event.end_time }}</span><br>
                            <span>{{ event.address }}</span><br>
                            <span>{{ event.host_name }}さんがイベントを作成しました。</span>
                        </div>
                    </v-card-title>
                    <v-card-actions>
                        <v-btn flat color="orange">
                            <router-link :to="{ name: 'event_detail', params: { id: event.id } }">詳細</router-link>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </template>
            <template v-else>
                <p>開催可能なイベントがありません。</p>
            </template>
        </v-flex>
    </v-layout>
</template>

<script>

import axios from 'axios'

export default {
    name: 'EventIndexComponent',
    data: () => {
        return {
            events: {},
        }
    },
    mounted: function(){
        this.get_events()
    },
    methods: {
        get_events: function(){
            let data = {}
            axios.get('/api/event')
            .then((response) => {
                const data = response.data
                console.log(data)
                if(data.result == 'success'){
                    this.events = data.events
                }
            })
            .catch(e => {
                console.log(e)
            })
        }
    }

}

</script>