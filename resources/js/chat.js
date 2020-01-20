import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '3772dd2af7092117af41',
  wsHost: window.location.hostname,
  wsPort: 443,
  disableStats: true,
  forceTLS: false,
  auth: {
    headers: {
      // eslint-disable-next-line
      'X-CSRF-TOKEN': token
    }
  }
})
