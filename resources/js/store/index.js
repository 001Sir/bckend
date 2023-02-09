import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'

// Modules
import auth from './modules/auth'
import blog from './modules/blog'
import dashboard from './modules/dashboard'
import partners from './modules/partners'
import jobs from './modules/jobs'
import categories from './modules/categories'
import skills from './modules/skills'
import jobtypes from './modules/jobtypes'
import postdurations from './modules/postdurations'
import subscription from './modules/subscription'

export default new createStore({
    plugins:[
        createPersistedState()
    ],
    modules:{
        auth,
        blog,
        dashboard,
        partners,
        jobs,
        categories,
        skills,
        jobtypes,
        postdurations,
        subscription
    }
})