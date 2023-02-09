import { createRouter, createWebHistory } from "vue-router";
import store from "../store";
import Login from "../pages/Login.vue";
import Dashboard from "../components/Dashboard.vue";
import Home from "../pages/Home.vue";
import Users from "../pages/Users.vue";
import Jobs from "../pages/Jobs.vue";
import Partners from "../pages/Partners.vue";
import ContentManagementIndex from "../pages/content-management/index.vue";
import ContentManagement from "../pages/content-management/ContentManagement.vue";
import NewBlogPost from "../pages/content-management/blog/new.vue";
import EditBlogPost from "../pages/content-management/blog/id.vue";
import Subscriptions from "../pages/Subscriptions.vue";
import Settings from "../pages/Settings.vue";

const routes = [
    {
        path: "/admin/login",
        name: "login",
        component: Login,
        meta: {
            title: "Login",
            middleware: "guest",
        },
    },
    {
        path: "/admin",
        component: Dashboard,
        meta: {
            middleware: "auth",
        },
        children: [
            {
                path: "/admin/home",
                name: "home",
                component: Home,
                meta: {
                    title: 'Home'
                }
            },
            {
                path: "/admin/users",
                name: "users",
                component: Users,
                meta: {
                    title: 'Users'
                }
            },
            {
                path: "/admin/jobs",
                name: "jobs",
                component: Jobs,
                meta: {
                    title: 'Jobs'
                }
            },
            {
                path: "/admin/partners",
                name: "partners",
                component: Partners,
                meta: {
                    title: 'Partners'
                }
            },
            {
                path: "/admin/content-management",
                name: "content_management",
                component: ContentManagementIndex,
                meta: {
                    title: 'Content Management'
                },
                children: [
                    { path: "", component: ContentManagement },
                    {
                        path: "blog",
                        children: [
                            { path: 'post/:id', name: 'blog.index', component: EditBlogPost },
                            { path: 'post/new', name: 'blog.new', component: NewBlogPost }
                        ]
                    },
                ],
            },
            {
                path: "/admin/subscriptions",
                name: "subscriptions",
                component: Subscriptions,
                meta: {
                    title: 'Subscriptions'
                }
            },
            {
                path: "/admin/settings",
                name: "settings",
                component: Settings,
                meta: {
                    title: 'Settings'
                }
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta.title} - ${import.meta.env.VITE_APP_NAME}`;
    if(to.path == "/admin") next({ name: "home" });
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated ) {
            next({ name: "home" });
        }
        next();
    } else {
        if (store.state.auth.authenticated) {
            next();
        } else {
            next({ name: 'login' });
        }
    }
});

export default router;
