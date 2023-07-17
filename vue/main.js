import Vue from 'vue';
import VueRouter from 'vue-router';
import { store } from './store';
import App from './app.vue';
import Communication from './scripts/communication';

const NotFound = { template: '<div>Ups, wir konnten leider nicht finden was Sie suchen.</div>' }
import CourseOverview from './components/courseOverview';
import CourseCompletion from './components/courseCompletion';
import LearningStrategy from './components/learningStrategies';



function init(courseid, fullPluginName, userid, isModerator, policyAccepted) {
    
    // We need to overwrite the variable for lazy loading.
    __webpack_public_path__ = M.cfg.wwwroot + '/course/format/serial2/amd/build/';

    Communication.setPluginName(fullPluginName);
    Vue.use(VueRouter);

    store.commit('setCourseid', courseid);
    store.commit('setisModerator', isModerator);
    store.commit('setPluginName', fullPluginName);
    store.commit('setUserid', userid);
    store.commit('setPolicyAccepted', policyAccepted);
    store.dispatch('loadComponentStrings');

    // You have to use child routes if you use the same component. Otherwise the component's beforeRouteUpdate
    // will not be called.
    const routes = [
          { path: '/', component: CourseOverview, props: true},
          { path: '/completion', component: CourseCompletion, props: true},
          { path: '/strategies/:strategy?', component: LearningStrategy, props: true},
          { path: '*', component: NotFound}
      ];

    // base URL is /mod/vuejsdemo/view.php/[course module id]/
    const currenturl = window.location.pathname;
    const base =
        currenturl.substr(0, currenturl.indexOf('.php')) +
        '.php/?id=' +
        courseid +
        '/';

    const router = new VueRouter({
        mode: 'hash',
        routes,
        base,
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

    // Hide resources for students, that did not agree to the informed consent
    console.log('-- policy accepted? '+policyAccepted)
    if (policyAccepted == false && courseid == 24) {
        $('.activity.quiz.modtype_quiz').hide();
        $('.activity.modtype_longpage').hide();
        $('.activity.modtype_usenet').hide();
        $('.activity.modtype_safran').hide();
    }
    
    
    var serial2 = new Vue({
        el: '#app',
        store,
        router,
        render: (h) => h(App),
    });

}

export { init };
