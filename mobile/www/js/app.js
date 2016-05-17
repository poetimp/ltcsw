// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('app', ['ionic', 'app.controllers', 'app.routes', 'app.services', 'app.directives'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);
    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.service('HttpService', function($http) {
 return {
    getAllEvents: function() {
       return $http.get('db/allevents.php')
         .then(function (response) {
           console.log('(getAllEvents) Post', response);
           return response.data;
         });
     },
     
     authenticate: function(user) {
        return $http.get('db/authenticate.php?u=' + user.lastName + '&p=' + user.password)
        .then(function (response) {
          console.log('(authenticate) Post', response);
          return response.data;
        });
      },
      
      myevents: function(user) {
         return $http.get('db/myevents.php?p=' + user.password)
         .then(function (response) {
         console.log('(myevents) Post', response);
         return response.data;
       })
      },

      getContactList: function() {
         return $http.get('db/contactList.php')
         .then(function (response) {
         console.log('(contacts) Post', response);
         return response.data;
       })
      },

      getContactDetail: function(contactID) {
         return $http.get('db/contactDetail.php?p='+contactID)
         .then(function (response) {
         console.log('(contactDetail) Post', response);
         return response.data;
       })
      }


 }
})

.service('formData', function() {
   return {
     form: {},
     getForm: function() {
       return this.form;
     },
     updateForm: function(form) {
       this.form = form;
     }
   }
  })
  
.service('dbData', function()
{
   return {
      getUserInfo: function()
      {
         var db = new PouchDB('ltcsworg');
         db.get('userinfo')
         .then(function (userinfo) 
         {
            return userinfo.user;
         })
         .catch(function (err) 
         {
            console.log(err);
            return {}
         })
      },
      
      saveUserInfo: function(user)
      {
         var db = new PouchDB('ltcsworg');
         db.put({
            _id: 'userinfo',
            user: user
          }).then(function (response) {
             console.log('User login information saved');
          }).catch(function (err) {
            console.log(err);
          })
      },
   }
})
