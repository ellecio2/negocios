import _ from "lodash";
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
/*Pusher.logToConsole = true;*/

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "d3448edc2806665833a2",
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "us2",
    wsHost: import.meta.env.VITE_PUSHER_HOST
        ? import.meta.env.VITE_PUSHER_HOST
        : `ws-us2.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

/*console.log('Echo instance:', window.Echo);*/

window.Echo.channel('orders')
    .listen('OrderStatusUpdated', (e) => {
        //Livewire.emit('notificationReceived', e);
        Livewire.emitTo('seller-notifications', 'notificationReceived', e);
        Livewire.emitTo('notifications', 'notificationReceived', e);
    })
    .error((error) => {
        console.error('Error al escuchar el canal:', error);
    });

/*window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('Conectado a Pusher');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('Desconectado de Pusher');
});

window.Echo.connector.pusher.connection.bind('error', function(error) {
    console.error('Error de conexión a Pusher:', error);
});*/
