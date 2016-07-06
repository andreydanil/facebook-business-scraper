<?php include "config.php"; ?><!DOCTYPE html>
<html lang="en" data-ng-app="FacebookBusinessScraper">
<head>
    <title>Brightery Facebook Business Scraper</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-sanitize/1.5.7/angular-sanitize.min.js"></script>
    <script src="ng-csv.min.js"></script>
    <style>


        .thumbnail{
            position: relative;
            z-index: 0;
        }

        .thumbnail:hover{
            background-color: transparent;
            z-index: 50;
        }

        .thumbnail span{ /*CSS for enlarged image*/
            position: absolute;
            background-color: lightyellow;
            padding: 5px;
            left: -1000px;
            border: 1px dashed gray;
            visibility: hidden;
            color: black;
            text-decoration: none;
        }

        .thumbnail span img{ /*CSS for enlarged image*/
            border-width: 0;
            padding: 2px;
        }

        .thumbnail:hover span{ /*CSS for enlarged image on hover*/
            visibility: visible;
            top: 0;
            left: 60px; /*position where enlarged image should offset horizontally */

        }



        .tooltip {
            /*give the thumbnails a frame*/
            background-color: #eae9d4; /*frame colour*/
            padding: 6px; /*frame size*/
            /*add a drop shadow to the frame*/
            -webkit-box-shadow: 0 0 6px rgba(132, 132, 132, .75);
            -moz-box-shadow: 0 0 6px rgba(132, 132, 132, .75);
            box-shadow: 0 0 6px rgba(132, 132, 132, .75);
            /*and give the corners a small curve*/
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            /*max-width: 300px;*/
            /*max-height: 300px;*/
        }

        .tooltip-arrow {
            display: none;
        }

        .tooltip-inner {
            background-color: transparent;
            max-width: none;
        }

        .spinner {
            display: inline-block;
            opacity: 0;
            width: 0;

            -webkit-transition: opacity 0.25s, width 0.25s;
            -moz-transition: opacity 0.25s, width 0.25s;
            -o-transition: opacity 0.25s, width 0.25s;
            transition: opacity 0.25s, width 0.25s;
        }

        .has-spinner.active {
            cursor: progress;
        }

        .has-spinner.active .spinner {
            opacity: 1;
            width: auto; /* This doesn't work, just fix for unkown width elements */
        }

        .has-spinner.btn-mini.active .spinner {
            width: 10px;
        }

        .has-spinner.btn-small.active .spinner {
            width: 13px;
        }

        .has-spinner.btn.active .spinner {
            width: 16px;
        }

        .has-spinner.btn-large.active .spinner {
            width: 19px;
        }
        .enlarge{
            cursor: pointer;
        }
    </style>
</head>
<body data-ng-controller="mainCtrl">

<div class="container">
    <h1>Brightery Facebook Business Scraper</h1>

    <p>Search up to 1000 results using keywords such as "Microsoft" or "Restaurants London"</p>

    <form class="form-horizontal" role="form" data-ng-submit="search()">
        <div class="form-group  has-feedback">
            <div class="col-sm-6">
                <input type="text" class="form-control" id="search" placeholder="Search" data-ng-model="query">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
            <div class="col-sm-2">
                <div class="dropdown-group">
                    <select class="form-control" data-ng-model="type" data-ng-init="type = 'page'">
                        <option value="page">Page</option>
                        <option value="place">Place</option>
                        <option value="group">Group</option>
                        <option value="user">User</option>
                        <option value="event">Event</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="dropdown-group">
                    <select class="form-control" data-ng-model="num" data-ng-init="num = '20'">
                        <option value="20">20</option>
                        <option value="50" disabled>50</option>
                        <option value="100" disabled>100</option>
                        <option value="250" disabled>250</option>
                        <option value="500" disabled>500</option>
                        <option value="1000" disabled>1000</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-warning has-spinner" data-ng-class="loading ? 'active' : null">
                    <span class="spinner"><i class="icon-spin icon-refresh"></i></span>
                    Search
                </button>
            </div>
        </div>
    </form>

    <form class="form-horizontal" role="form">
        <div class="form-group  has-feedback">
            <div class="col-sm-9">
                <p>You can filter the entries from this filter input:</p>
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="filter" placeholder="Filter" data-ng-model="filterEntries">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>
    </form>

    <table class="table table-hover">
        <thead>
        <tr>
            <th data-ng-repeat="field in fields">{{field}}</th>

        </tr>
        </thead>
        <tbody data-ng-show="resType == 'page'">
        <tr data-ng-repeat="item in items | filter:filterEntries">
            <td alt="{{item.about}}">{{item.id}}</td>
            <td><a data-ng-href="{{item.link}}">{{item.name}}</a>
                <i data-ng-show="item.is_verified == true" class="fa fa-check-square-o"></i>
            </td>
            <td>{{item.contact_address}}</td>
            <td>{{item.phone}}</td>
            <td>{{item.emails.join()}}</td>
            <td>{{item.website}}</td>
            <td>{{item.fan_count}}</td>
        </tr>
        </tbody>

        <tbody data-ng-show="resType == 'group'">
        <tr data-ng-repeat="item in items | filter:filterEntries">
            <td alt="{{item.about}}">{{item.id}}</td>
            <td>
                <img ng-src="{{ item.icon }}"/>
                <a data-ng-href="{{item.link}}">{{item.name}}</a>
            </td>
            <td>{{item.description | limitTo:100 }}
            </td>
            <td>{{item.email}}</td>
            <td>{{item.privacy}}</td>
        </tr>
        </tbody>

        <tbody data-ng-show="resType == 'place'">
        <tr data-ng-repeat="item in items | filter:filterEntries">
            <td alt="{{item.about}}">{{item.id}}</td>
            <td><a data-ng-href="{{item.link}}">{{item.name}}</a>
                <i data-ng-show="item.is_verified == true" class="fa fa-check-square-o"></i>
            </td>
            <td>Street: {{item.location.street}}, City: {{item.location.city}}, State: {{item.location.state}}, Country:
                {{item.location.country}}, Zip: {{item.location.zip}}
            </td>
            <td><a ng-href="https://www.google.com.eg/maps/@{{item.location.latitude}},{{item.location.longitude}},20z"
                   target="_blank">MAP</a></td>
        </tr>
        </tbody>

        <tbody data-ng-show="resType == 'user'">
        <tr data-ng-repeat="item in items | filter:filterEntries">
            <td>{{item.id}}</td>
            <td>
                <img class="img-circle" ng-src="{{ item.picture.data.url }}"/>
                <a data-ng-href="{{item.link}}">{{item.name}}</a>
                <i data-ng-show="item.is_verified == true" class="fa fa-check-square-o"></i>
            </td>
            <td>{{item.age_range}}</td>
            <td>{{item.email}}</td>
            <td>{{item.gender}}</td>
            <td>{{(item.devices) | json}}</td>
        </tr>
        </tbody>

        <tbody data-ng-show="resType == 'event'">
        <tr data-ng-repeat="item in items | filter:filterEntries">
            <td><a class="thumbnail" class="enlarge">{{item.id}} <span><img ng-src="{{ item.cover.source }}" /></span> </a></td>
            <td>

                <a data-ng-href="http://fb.com/{{item.id}}" class="enlarge" target="_blank">{{item.name| limitTo: 50}} </a>
                <i data-ng-show="item.is_verified == true" class="fa fa-check-square-o"></i>
            </td>
            <td>{{item.attending_count}}</td>
            <td>{{item.place.name}}</td>
            <td><a href="http://fb.com/{{item.owner.id}}">{{item.owner.name}}</a></td>
            <td>{{item.type}}</td>
            <td>{{item.start_time}} - {{item.end_time}}</td>
        </tr>
        </tbody>


    </table>

    <button class="btn btn-success pull-right"
            
            ng-csv="items" csv-header="getHeader()" filename="{{ query }}.csv" field-separator="{{separator}}"
            decimal-separator="{{decimalSeparator}}">Export to CSV
    </button>
<p>Powered By <a href="http://www.brightery.com.eg">Brightery</a></p>
</div>

<script>
    var Brightery = angular.module('BrighteryFacebookBusinessScraper', ['ngSanitize', 'ngCsv']);
    Brightery.controller('mainCtrl', function ($scope, $http) {
        $scope.login_status = false;
        $scope.items = [];
        $scope.getHeader = function () {
            return $scope.eFields;
        };
        $scope.search = function () {
            $scope.loading = true;

            if (!$scope.query) {

                $scope.SystemMessage = "You must insert a search query first";
                $("#myModal").modal();


                $scope.loading = false;

                return;
            }

            if ($scope.type != 'place' && $scope.type != 'page' && !$scope.login_status) {
                return $scope.login();
            }

            $http({
                url: 'api.php',
                method: 'post',
                data: {query: $scope.query, limit: $scope.num, accessToken: $scope.accessToken, type: $scope.type}
            }).then(function (res) {
                $scope.eFields = res.data.eFields;
                $scope.fields = res.data.fields;
                $scope.items = res.data.data;
                $scope.resType = res.data.type;
                $scope.loading = false;

                if ($scope.resType == 'event') {
                    setTimeout(activeTooltip, 1000);

                }
            });
        };

        function activeTooltip() {
            $('a[data-toggle="tooltip"]').tooltip({
                animated: 'fade',
                placement: 'right',
                html: true
            });

        }

        $scope.export = function () {
            alert('Sorry, this feature is disabled on the demo version');
        }

        $scope.login = function () {
            FB.getLoginStatus(function (response) {
                if (response.status === 'connected') {
                    $scope.accessToken = response.authResponse.accessToken;
                    $scope.login_status = true;
                }
                else {
                    FB.login();
                    $scope.search();
                }
            });
        }


        window.fbAsyncInit = function () {
            FB.init({
                appId: '<?= FACEBOOK_APP_ID ?>',
                xfbml: true,
                version: 'v2.6'
            });

            FB.getLoginStatus(function (response) {
                if (response.status === 'connected') {
                    $scope.accessToken = response.authResponse.accessToken;
                    $scope.login_status = true;
                }
            });

        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));


    });

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-79608829-1', 'auto');
    ga('send', 'pageview');


</script>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> System Message</h4>
            </div>
            <div class="modal-body">
                <span>{{SystemMessage}}</span>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default btn-default pull-left" data-dismiss="modal"><span
                        class="glyphicon glyphicon-remove"></span> Close
                </button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
