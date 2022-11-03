
var app = angular.module( "projectManagement", ["ngRoute","ngSanitize","ui.bootstrap","checklist-model","angularUtils.directives.dirPagination"] );
var baseUrl = "http://localhost/project-management/server/index.php/";
//var baseUrl = "http://localhost/project-management/server/index.php/";

app.filter('capitalize', function() {
    return function( input ) {
      return (angular.isString(input) && input.length > 0) ? input[0].toUpperCase() + input.substr(1).toLowerCase() : input;
    }
});

app.config(function( $routeProvider, $compileProvider ){

	$compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|javascript):/);

	var loginStatus = function( $q, $location, $log, server, utils ){

		var deffer = $q.defer();

		server.getLoginStatus().then(function( response ){

			if( response.status ){
				var info = response.data;
				$log.info("User is Logged In.");

				server.getMenu().then(function( response ){
					info.menu = response.data;
					info.currRoute = utils.getUriSegment(1);

					//Finding Current Parent Menu
					angular.element.each( info.menu , function( key, parent ){

						if( parent.children !== null ){
							angular.element.each( parent.children, function( key, child ){

								if( child.slug == '#/'+info.currRoute ){
									info.currentParentMenuID = parent.ID;
								}
								
							});
						}
						
					});

					deffer.resolve(info);
				});

				
			}else{
				deffer.reject();
				$log.warn("User is not Logged In.");
				$location.path( '/' );
			}	

		},function(){
			utils.redirectToErrorPage();
		});

		return deffer.promise;						
	} 

	$routeProvider
		.when("/", {
			templateUrl : "app/pages/login.html",
			controller  : "loginCtrl"
		})
		.when("/dashboard",{
			templateUrl : "app/pages/dashboard.html",
			controller  : "dashboardCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/user",{
			templateUrl : "app/pages/users.html",
			controller  : "userCtrl",
			resolve     : { "loggedInUser": loginStatus } 
		})
		.when("/user/:action",{
			templateUrl : "app/pages/users.html",
			controller  : "userCtrl",
			resolve     : { "loggedInUser": loginStatus } 
		})
		.when("/role",{
			templateUrl : "app/pages/roles.html",
			controller  : "roleCtrl",
			resolve     : { "loggedInUser": loginStatus } 
		})
		.when("/skill",{
			templateUrl : "app/pages/skills.html",
			controller  : "skillCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/dbMigrate",{
			templateUrl : "app/pages/dbMigrate.html",
			controller  : "dbMigrateCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/estimation",{
			templateUrl : "app/pages/estimation.html",
			controller  : "estimationCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/estimation/view/:ID",{
			templateUrl : "app/pages/viewEstimation.html",
			controller  : "viewEstimationCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/estimation/edit/:ID",{
			templateUrl : "app/pages/estimation-edit.html",
			controller  : "estimationEditCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/estimation/:action",{
			templateUrl : "app/pages/estimation.html",
			controller  : "estimationCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/project",{
			templateUrl : "app/pages/project.html",
			controller  : "projectCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/project/view/:ID",{
			templateUrl : "app/pages/viewProject.html",
			controller  : "viewProjectCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/settings",{
			templateUrl : "app/pages/settings.html",
			controller  : "settingsCtrl",
			resolve     : { "loggedInUser": loginStatus }
		})
		.when("/error",{
			templateUrl : "app/pages/error.html",
			controller  : "errorCtrl" 
		})
		.when("/profile",{
			templateUrl : "app/pages/profile.html",
			controller : "profileCtrl",
			resolve    : { "loggedInUser": loginStatus}
		})
		.otherwise({redirectTo : '/'});

});


