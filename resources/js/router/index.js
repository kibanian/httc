import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import base_component from '../components/BaseComponent.vue'

Vue.component('base-component', base_component)

import example from '../components/ExampleComponent.vue'
import tenniscourt_regist from '../components/TenniscourtRegistComponent.vue'
import event_regist from '../components/EventRegistComponent.vue'
import event_index from '../components/EventIndexComponent.vue'
import event_detail from '../components/EventDetailComponent.vue'
import user_regist from '../components/UserRegistComponent.vue'
import user_index from '../components/UserIndexComponent.vue'
import login from '../components/LoginComponent.vue'
import logout from '../components/LogoutComponent.vue'

const routes = [
    { path: '/', name: 'example', component: example, meta: { is_public: true } },
    { path: '/tenniscourt/regist', name: 'tenniscourt_regist', component: tenniscourt_regist },
    { path: '/event/regist', name: 'event_regist', component: event_regist },
    { path: '/event/', name: 'event_index', component: event_index },
    { path: '/event/:id', name: 'event_detail', component: event_detail },
    { path: '/user/regist', name: 'user_regist', component: user_regist },
    { path: '/user', name: 'user_index', component: user_index },
    { path: '/login', name: 'login', component: login, meta: { is_public: true } },
    { path: '/logout', name: 'logout', component: logout }
]

const router = new Router({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => !record.meta.is_public)){
        if(state.is_login){
            next()
        }else{
            next({ path: '/login', query: { redirect: to.fullPath } })
        }
    }else{
        next()
    }
})

export default router