var grid = {
    fetchData: function (scope, datatable, datatableConfig, url) {
        var url = datatableConfig.url;
        delete datatableConfig.url;
        var initInjector = angular.injector(["ng"]);
        var $http = initInjector.get("$http");

        $http.get(url).then(function (response) {
            scope.datatable = datatable(datatableConfig);
            scope.datatable.setData(response.data.data, response.data.recordsNumber);
        }, function (errorResponse) {
            // Handle error case
        });
    }
};

var datatableConfigDefault = {
    "edit": {
        "active": false,
        "columnMode": false
    },
    "actions": {
        "active": true,
        "canEdit": true,
        "canDelete": true,
        "canView": true
    },
    "group": {
        "active": false,
    },
    "compact": true,
    "pagination": {
        "mode": 'remote',
        "numberPageListMax": 3,
        "numberRecordsPerPage": 10,
    },
    "filter": {
        "active": true,
        "highlight": false,
        "columnMode": true,
    },
    "cancel": {
        "active": false, //Active or not
        "showButton": false//Show the cancel button in the toolbar
    },
};

var moduleA = angular.module('ngAppDemo', ['neogetDataTableServices']);
moduleA.controller('ngAppDemoController',
        ['$scope', 'datatable', '$http', '$window', function ($scope, datatable, $http, $window) {
                $scope.isCollapsed = false;
                grid.fetchData($scope, datatable, datatableConfig);
                $scope.redirectto = function (address) {
                    $window.location.href = address;
                }
            }]);

angular.element(document).ready(function () {
    angular.bootstrap(document.getElementById("mainController"), ["ngAppDemo", "advancefilter"]);
});