require("./bootstrap");

import { createApp } from "vue";
import router from "./router";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

import { abilitiesPlugin } from "@casl/vue";
import { defineAbilityFor } from './services/ability';

import Vue from 'vue';
import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

import Dashboard from "./pages/Dashboard.vue";
import Select2 from 'vue3-select2-component';
import MoncashModal from './components/transactions/MoncashModal.vue';
import NatcashModal from './components/transactions/NatcashModal.vue';

import Breadcrumb from './components/breadcrumb/Breadcrumb.vue';

import AddSystemBankAccount from './components/dashboard/AddSystemBankAccount.vue';

const getAuthUser = async () => {
    await axios.get("/api/getauthuser").then((response) => {
        const user = response.data;
        const ability = defineAbilityFor(user)
        
        console.log(ability.can('manage', 'all'));
        
        const app = createApp({ Dashboard });
        app.component("Pagination", Bootstrap5Pagination);
        app.component('Select2', Select2);
        app.component('moncashmodal', MoncashModal);
        app.component('natcashmodal', NatcashModal);
        app.component('AddSystemBankAccount', AddSystemBankAccount);
        app.component('Breadcrumb', Breadcrumb);
        app.use(abilitiesPlugin, ability);
        app.use(VueTelInput);
        app.use(router).mount("#app");
    });
};
getAuthUser();
