import "./bootstrap";
import { createApp } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faRefresh,
    faBold,
    faItalic,
    faHeading,
    faLink,
    faListUl,
    faListOl,
    faUndo,
    faRedo,
    faPencil,
    faCheck,
    faTrash,
    faCode,
    faEye,
    faClose,
    faChevronCircleLeft,
} from "@fortawesome/free-solid-svg-icons";
import router from "./router/index";
import App from "./components/App.vue";
import store from "./store";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

library.add({
    faRefresh,
    faBold,
    faItalic,
    faHeading,
    faLink,
    faListUl,
    faListOl,
    faUndo,
    faRedo,
    faPencil,
    faCheck,
    faTrash,
    faEye,
    faCode,
    faClose,
    faChevronCircleLeft,
});

const app = createApp(App);

app.component("font-awesome-icon", FontAwesomeIcon);
app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true,
});
app.use(store);
app.use(router);
app.mount("#app");
