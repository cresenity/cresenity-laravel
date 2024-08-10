<?php
namespace Cresenity\Laravel\CApp\Notification;

use Illuminate\Support\Arr;

class ServiceWorkerService {
    public function generate($config) {
        $driver = Arr::get($config, 'driver');
        $options = Arr::get($config, 'options');

        $output = '';

        if ($driver == 'firebase') {
            $output .= $this->firebaseScript($options) . PHP_EOL;
        }

        return $output;
    }

    protected function firebaseScript($options) {
        $jsonOptions = json_encode($options);

        return <<<JAVASCRIPT
importScripts('https://www.gstatic.com/firebasejs/9.2.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.2.0/firebase-messaging-compat.js');
firebase.initializeApp(${jsonOptions});
const messaging = firebase.messaging();
messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = payload.title;
  const notificationOptions = {
    body: payload.body,
    icon: payload.icon
  };

  self.registration.showNotification(notificationTitle,
    notificationOptions);
});

JAVASCRIPT;
    }
}
