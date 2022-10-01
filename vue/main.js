import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store';
import App from "./app.vue";
import Communication from './scripts/communication';


function init(courseid, fullPluginName, userid) {
    // We need to overwrite the variable for lazy loading.
    __webpack_public_path__ = M.cfg.wwwroot + '/course/format/ladtopics/amd/build/';

    Communication.setPluginName(fullPluginName);

    Vue.use(VueRouter);

    store.commit('setCourseid', courseid);
    store.commit('setPluginName', fullPluginName);
    store.commit('setUserid', userid);
    store.dispatch('loadComponentStrings');

    // You have to use child routes if you use the same component. Otherwise the component's beforeRouteUpdate
    // will not be called.
    /*const routes = [
        { path: '/', component: mainPage},
        { path: '/store', component: storePage},
        { path: '*', component: notFound}
    ];*/

    // base URL is /mod/vuejsdemo/view.php/[course module id]/
    const currenturl = window.location.pathname;
    const base = currenturl.substr(0, currenturl.indexOf('.php')) + '.php/?id=' + courseid + '/';
    
    const router = new VueRouter({
        mode: 'history',
        // routes,
        base
    });

    /*router.beforeEach((to, from, next) => {
        // Find a translation for the title.
        if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
            if (store.state.strings.hasOwnProperty(to.meta.title)) {
                document.title = store.state.strings[to.meta.title];
            }
        }
        next()
    });*/

    new Vue({
        el: '#app',
        store,
        // router,
        render: (h) => h(App),
    });
}

export { init };
