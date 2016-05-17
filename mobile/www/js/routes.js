angular.module('app.routes', [])

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider

  .state('menu.home', {
    url: '//home',
    views: {
      'side-menu21': {
        templateUrl: 'templates/home.html',
        controller: 'homeCtrl'
      }
    }
  })

  .state('menu.login', {
    url: '/login',
    views: {
      'side-menu21': {
        templateUrl: 'templates/login.html',
        controller: 'loginCtrl'
      }
    }
  })

  .state('menu', {
    url: '/side-menu21',
    templateUrl: 'templates/menu.html',
    abstract:true
  })

  .state('menu.mySchedule', {
    url: '/mysched',
    views: {
      'side-menu21': {
        templateUrl: 'templates/mySchedule.html',
        controller: 'myScheduleCtrl'
      }
    }
  })

  .state('menu.allEvents', {
    url: '/allsched',
    views: {
      'side-menu21': {
        templateUrl: 'templates/allEvents.html',
        controller: 'allEventsCtrl'
      }
    }
  })

  .state('menu.faq', {
    url: '/faq',
    views: {
      'side-menu21': {
        templateUrl: 'templates/faq.html',
        controller: 'faqCtrl'
      }
    }
  })

  .state('menu.about', {
    url: '/about',
    views: {
      'side-menu21': {
        templateUrl: 'templates/about.html',
        controller: 'aboutCtrl'
      }
    }
  })

  .state('menu.map', {
    url: '/map',
    views: {
      'side-menu21': {
        templateUrl: 'templates/map.html',
        controller: 'mapCtrl'
      }
    }
  })

  .state('menu.contacts', {
    url: '/contacts',
    views: {
      'side-menu21': {
        templateUrl: 'templates/contacts.html',
        controller: 'contactsCtrl'
      }
    }
  })

  .state('contactDetails', {
    url: '/contactdetails/:contactID',
    templateUrl: 'templates/contactDetails.html',
    controller: 'contactDetailsCtrl'
  })

$urlRouterProvider.otherwise('/side-menu21//home')



});
