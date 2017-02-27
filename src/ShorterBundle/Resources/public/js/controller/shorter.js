var app        = window.app;
var baseUrl    = window.baseUrl;

app.module.controller('ShorterCtrl', function($scope, $http) {
    "use strict";

    $scope.form         = {};
    $scope.errors       = {};
    $scope.response     = {};

    $scope.saveUrl = function () {
        $scope.errors       = {};
        $scope.response     = {};

        $http.post(baseUrl + 'shorturl/create', $scope.form)
            .then(function(response) {
                if (response.data) {
                    $scope.response = response.data;
                    $scope.form     = {};
                    $scope.urlForm.$setPristine();
                }
            },
            function(response) {
                if (response.data && response.data.errors) {
                    $scope.errors = response.data.errors;
                    console.log($scope.errors);
                }
            });



    };

});
