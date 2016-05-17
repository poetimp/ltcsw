angular.module('app.controllers', [])

.controller('homeCtrl', function($scope, dbData) 
{
   
})

.controller('loginCtrl', function($scope, formData, HttpService, dbData, $ionicLoading) {
   $scope.user = dbData.getUserInfo;

   $scope.submitForm = function(user) 
   {
     if (user.lastName && user.password) {
       console.log("Saving Form data");
       dbData.saveUserInfo(user);
       
       $ionicLoading.show({template: 'Loading...'});
       
       HttpService.authenticate(user)
       .then
       (
         function(response)
         {
           loggedIn = angular.fromJson(response);
           console.log(JSON.stringify(loggedIn));
           if (loggedIn.loggedIn == 1)
           {
              HttpService.myevents(user)
              .then
              (
                function(events)
                {
                   console.log("Saving my events"+JSON.stringify(events));
                   var db = new PouchDB('ltcsworg');
                   db.put({
                      _id: 'myEvents',
                      myEvents: events
                    }).then(function (events) {
                       console.log('Personal Events saved');
                    }).catch(function (err) {
                      console.log(err);
                    })

                }
              )
              $scope.message="Login successful! You may now go check your schedule";
           }
           else
           {
              $scope.message="Login unsuccessful!";
           }
           /* Beaulieu 1081 */
           $ionicLoading.hide();
         }
       )
     } else {
       $scope.message="Please enter both Last name and Participint ID or Non-Participant Password";
     }
   };
})

.controller('myScheduleCtrl', function($scope, dbData) {
   var db = new PouchDB('ltcsworg');
   db.get('myEvents').then(function (events) {
      console.log("Successfull retrieved event data:"+JSON.stringify(events.myEvents));
      $scope.events = events.myEvents;
   })
   .catch(function (err) {
      console.log(err);
   })
})

.controller('allEventsCtrl', function($scope,HttpService, $ionicLoading)
  {
    $ionicLoading.show({template: 'Loading...'});
    HttpService.getAllEvents()
    .then
    (
      function(response)
      {
        $scope.events = response;
        $ionicLoading.hide();
      }
    );
  }
)

.controller('faqCtrl', function($scope) {

})

.controller('aboutCtrl', function($scope) {

})

.controller('mapCtrl', function($scope) {

})

.controller('contactsCtrl', function($scope, HttpService, $ionicLoading) {
   $ionicLoading.show({template: 'Loading...'});
   HttpService.getContactList()
   .then
   (
     function(response)
     {
       $scope.contacts = response;
       $ionicLoading.hide();
     }
   );

})

.controller('contactDetailsCtrl', function($scope, $stateParams, HttpService, $ionicLoading) {
   $scope.contactID = $stateParams.contactID;
   $ionicLoading.show({template: 'Loading...'});
   HttpService.getContactDetail($scope.contactID)
   .then
   (
     function(response)
     {
       $scope.contact = response;
       $ionicLoading.hide();
     }
   );

})
