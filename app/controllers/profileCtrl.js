app.controller("profileCtrl",function( $scope, $filter, utils, server, loggedInUser ){
	
	utils.setTitle( 'Profile | Project Management' );
	$scope.page = "Profile",
	$scope.addBtn = false,
	$scope.activeMenu = "Dashboard",
	$scope.loggedInUser = loggedInUser;

	$scope.editedProfile = { 'user_role_id':loggedInUser.role_id,'ID':loggedInUser.user_id, 'username':loggedInUser.username, 'first_name': loggedInUser.first_name, 'last_name' : loggedInUser.last_name};

	server.getSkill().then(function( response ){
		$scope.skills = response.data;
	});

	server.getUserSkills( loggedInUser.user_id ).then( function(response){
		if( response.status ){
			$scope.editedProfile.skillId =[];

			for( key in response.data ){
				$scope.editedProfile.skillId.push( response.data[ key ].ID );
			}
		}
	});

	$scope.update = function( u ){
		
		server.updateProfile( u ).then( function( response ){

			if( response.status ){
				$scope.loggedInUser.first_name = response.data.first_name;
				$scope.loggedInUser.last_name = response.data.last_name;
			}

			utils.toast( response.message );

		});
	}
});