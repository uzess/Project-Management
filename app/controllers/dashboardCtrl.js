
app.controller("dashboardCtrl",function( $scope, $filter, utils, server, loggedInUser ){
	
	utils.setTitle( 'Dashboard | Project Management' );
	$scope.page = "Dashboard",
	$scope.addBtn = false,
	$scope.activeMenu = "Dashboard",
	$scope.loggedInUser = loggedInUser,
	$scope.projectLimit = 6,
	$scope.projects = [],
	$scope.filteredProjects = [],
	$scope.completedProjects = {},
	$scope.projectOptions = [
	{ 'name':'All','value':'all' },
	{ 'name':'On Going', 'value' : 'ongoing' },
	{ 'name':'Completed', 'value' : 'completed' },
	{ 'name':'Rejected', 'value' : 'rejected' },
	{ 'name':'Postponed', 'value' : 'postponed'}
	],
	$scope.currentProjectStatus = 'all',
	$scope.skill = {};

	server.getUserSkills( loggedInUser.user_id ).then( function( response ){

		if( response.status ){
			$scope.skill = response.data;
		}
		
	});

	server.getProjectByStatus( 'Completed',10 ).then( function( response ){
		if( response.status ){
			$scope.completedProjects = response.data;
			$scope.completedProjectNum = response.total_rows;
		}
	});

	$scope.getProjects = function( limit ){

		var filter = 'true';

		if( loggedInUser.role == 'Super' ){
			filter = '';
		}

		server.getPartialProject( limit, filter ).then( function( response ){

			if(response.status){
				$scope.projects = response.data;
			}

		});
	}

	$scope.getProjects($scope.projectLimit);
	

});