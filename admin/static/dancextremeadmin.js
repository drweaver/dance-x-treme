/*global angular*/

var app = angular.module('dancextremeadmin', [ ]);

app.controller('navController', function($scope) {
    $scope.tabs = [
        { id: "venues", name: "Venues" },
        { id: "gallery", name: "Gallery" },
        { id: "holiday_gallery", name: "Holiday Gallery" }
        ] ;
      
    $scope.activeTab = $scope.tabs[0];    
    $scope.active = function(tab) {
        $scope.activeTab = tab;
    };
    $scope.isActive = function(tab) {
        return tab.id === $scope.activeTab.id;  
    };
});

app.controller('venueController', function($scope, $http, $timeout) {

    $scope.selectedVenue = {};
    $scope.venues = [];
    $scope.selectedClass = {};
    $scope.success = undefined;
    $scope.submitting = false;

    $scope.deleteVenue = function(venueId) {
      console.info( "deleting " + venueId);  
      for( var i=0; i<$scope.venues.length; i++ ) {
        if( $scope.venues[i].id === venueId ) {
            $scope.venues.splice(i, 1);
            break;
        }
      }
      $scope.selectedVenue = $scope.venues[0];
    };

    $http.get('https://storage.googleapis.com/dance-x-treme-data/dance_venues.txt?_='+ new Date().getTime()).success(function(data) {
        $scope.venues = data;   
        $scope.selectedVenue = $scope.venues[0];
    });
    
    $scope.submit = function() {
    	$scope.submitting = true;
        $http.post('update_venue.php', $scope.venues).success(function() {
           console.log("venue update successful"); 
           $scope.success = true;
           $timeout(()=>{ $scope.success = undefined; }, 5000);
           $scope.submitting = false;
        }).error(()=>{
        	$scope.success = false;
        	$timeout(()=>{ $scope.success = undefined; }, 5000);
        	$scope.submitting = false;        
        });
    };
    
});

app.controller('galleryController', function($scope, $http, $timeout) {
   
   $scope.currentAlbumId=0;
   $scope.albums = [];
   $scope.success = undefined;
   $scope.submitting = false;
   $scope.datePattern = /\d\d\d\d-\d\d-\d\d/;


   $scope.init = function( data_url, post_url ) {
        $scope.data_url = data_url;
        $scope.post_url = post_url;
        $http.get($scope.data_url+'?_='+ new Date().getTime()).success(function(data) {
            $scope.albums = data;   
        });    
   };

    
    $scope.thumbnailFix = function() {
        var t = $scope.albums[$scope.currentAlbumId].thumbnail;
        // from: =w925-h784-no
        //   to: =w160-h160-p-k-no
        $scope.albums[$scope.currentAlbumId].thumbnail = t.replace(/=w\d+-h\d+-no/, '=w160-h160-p-k-no');
    };
    
    $scope.submit = function() {
    	$scope.submitting = true;
        $http.post($scope.post_url, $scope.albums).success(function() {
           console.log("gallery update successful"); 
           $scope.success = true;
           $timeout(()=>{ $scope.success = undefined; }, 5000);
           $scope.submitting = false;
        }).error(()=>{
        	$scope.success = false;
        	$timeout(()=>{ $scope.success = undefined; }, 5000);
        	$scope.submitting = false;
        });
    };
    
});
