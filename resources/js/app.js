/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('loading-spinner', require('./components/LoadingSpinner.vue').default);


Vue.mixin({
    mounted() {
        console.log('done');
    },
    methods: {
        notifyError(text) {
            new Noty({
                text,
                type: "error"
            }).show();
        },
        numberFormat(number) {
            const opt = {
                style: "currency",
                currency: "USD",
            }
            var numberFormat = new Intl.NumberFormat("en-US", opt);
            return numberFormat.format(number)
        },

        alertConfirmation(cbSuccess, text = "") {
            swal({
                title: "Warning!",
                text: text ?? "Are you sure to continue?",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "No",
                        value: null,
                        visible: true,
                        className: "bg-secondary",
                        closeModal: true,
                    },
                    restore: {
                        text: "Yes",
                        value: true,
                        visible: true,
                        className: "bg-success",
                    }
                },
            })
                .then(async (value) => {
                    cbSuccess(value)
                });
        }
    }
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
