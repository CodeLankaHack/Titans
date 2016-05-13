document.addEventListener('deviceready', function () {
    // Enable to debug issues
    window.plugins.OneSignal.setLogLevel({ logLevel: 0, visualLevel: 0 });

    //the callBack function called when we click on the notification received
    var notificationOpenedCallback = function (jsonData) {
        alert("Notification is received!");
    };

    //init function to use OneSignal service and GCM sender ID
    window.plugins.OneSignal.init("933f87d2-b68c-499d-a865-7e3be7badd9f",
                                   { googleProjectNumber: "382281090220" },
                                   notificationOpenedCallback);
    //subscribe to the service
    window.plugins.OneSignal.setSubscription(true);
    //activating the reception of push notification when the app is working also
    window.plugins.OneSignal.enableNotificationsWhenActive(true);
}, false);