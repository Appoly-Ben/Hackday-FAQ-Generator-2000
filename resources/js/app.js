import './bootstrap';
import { createApp } from "vue";

import QAndA from './components/QAndA.vue';

const app = createApp({});

app.component('q-and-a', QAndA);

app.mount('#app');