<template>
    <v-app>
        <v-navigation-drawer
            fixed
            v-model="drawer"
            app
        >
            <v-list dense>
                <template v-for="item in links">
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                <router-link :to="{ name: item.router_link }">{{ item.name }}</router-link>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar color="indigo" dark fixed app>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>HTTC</v-toolbar-title>
        </v-toolbar>
        <v-content>
            <v-container fluid fill-height>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer color="indigo" app inset>
            <span class="white--text">&copy; HTTC</span>
        </v-footer>
    </v-app>
</template>

<script>

export default {
    name: 'BaseComponent',
    data: () => {
        return {
            drawer: false,
            links: [
                { icon: 'home', router_link: 'example', name: 'Home', is_public: true },
                { icon: 'collections', router_link: 'tenniscourt_regist', name: 'テニスコート登録', is_public: false },
                { icon: 'mms', router_link: 'event_regist', name: 'イベント登録', is_public: false },
                { icon: 'mms', router_link: 'event_index', name: 'イベント一覧', is_public: false },
                { icon: 'group_add', router_link: 'user_regist', name: 'メンバー登録', is_public: false },
                { icon: 'group', router_link: 'user_index', name: 'メンバー一覧', is_public: false },
                { icon: 'group', router_link: 'login', name: 'ログイン', is_public: true },
                { icon: 'group', router_link: 'logout', name: 'ログアウト', is_public: false }
            ]
        }
    },
    computed: {
        logined: function(){
            return state.is_login
        }
    }
}

</script>