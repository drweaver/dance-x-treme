/*global angular*/

var app = angular.module('dancextremeadmin', [ ]);

app.controller('navController', function($scope) {
    $scope.tabs = [
        { id: "venues", name: "Venues" },
        { id: "gallery", name: "Gallery" }
        ] ;
      
    $scope.activeTab = $scope.tabs[0];    
    $scope.active = function(tab) {
        $scope.activeTab = tab;
    };
    $scope.isActive = function(tab) {
        return tab.id == $scope.activeTab.id;  
    };
});

app.controller('venueController', function($scope, $http) {

    $scope.selectedVenue = {};
    $scope.venues = [];
    $scope.selectedClass = {};

    $scope.deleteVenue = function(venueId) {
      console.info( "deleting " + venueId);  
      for( var i=0; i<$scope.venues.length; i++ ) {
        if( $scope.venues[i].id == venueId ) {
            $scope.venues.splice(i, 1);
            break;
        }
      }
      $scope.selectedVenue = $scope.venues[0];
    };

    $http.get('dance_venues.txt?_='+ new Date().getTime()).success(function(data) {
        $scope.venues = data;   
        $scope.selectedVenue = $scope.venues[0];
    });
    
    $scope.submit = function() {
        $http.post('venue_update.php', $scope.venues).success(function(data) {
           console.log("update successful"); 
        });
    };
    
});
