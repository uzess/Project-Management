app.controller( "projectCtrl",function( $scope, $uibModal, $location, server, utils, loggedInUser){

	utils.setTitle( "Project || Project Management" );

	$scope.loggedInUser = loggedInUser,
	$scope.page = "Projects",
	$scope.activeMenu = "Projects",
	$scope.addBtn = true,
	$scope.pagingConfig = {},
	$scope.fromYear = (utils.getCurrentYear()).toString(),
	$scope.toYear = utils.getCurrentYear().toString(),
	
	$scope.sortKey = 'ID',
	$scope.reverse = true;

	$scope.user = { projectIDs : [] };
	server.getProjectYears().then( function( response ){
		if( response.status ){
			$scope.years = response.data;
		}
	});

	utils.pagingConfig().then( function( response ){
		$scope.pagingConfig = response;
	});

	var onError = function( reason ){
		$location.path( "/error" );
		utils.toast( reason.statusText );
	}

	var onGetProject = function( response ){

		if( response.status ){
			$scope.projects = response.data;
			//var temp
			
		}else{
			$scope.projects = [];
			utils.toast( response.message );
		}

	}

	$scope.getData = function(){
		server.getProjectBetweenYears( $scope.fromYear, $scope.toYear ).then( onGetProject, onError );
	}

	$scope.getData();

    $scope.selectToggle = function(){
        if( $scope.toggle ){
            $scope.user.projectIDs = [];
            $scope.toggle = false;
        }else{
            $scope.user.projectIDs = $scope.projects.map(function(item) { return item.ID; });
            $scope.toggle = true;   
        }

    }

	var openModal = function( param ){
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'projectForm.html',
			controller: 'projectFormCtrl',
			size: 'lg',
			resolve:  {
				items : function(){
					return param;
				}
			}
		});
	}

	$scope.open = function () { 

		var param = {
			action : "save",
			projects : $scope.projects,
		};

		openModal( param ); 		 
	};

	$scope.editedProject = {};

	$scope.edit = function( p ){

		$scope.editedProject.name = p.name;
		$scope.editedProject.description = p.description;
		$scope.editedProject.ID = p.ID;
		$scope.editedProject.status = p.status;

		if( p.started_date !== null )
			$scope.editedProject.started_date = new Date(p.started_date);
		if( p.completed_date !== null )
			$scope.editedProject.completed_date = new Date(p.completed_date);
		
		$scope.editedProject.eID = p.eID;
		$scope.editedProject.seeEstimation = false;
		$scope.editedProject.uID = [];

		for( key in p.users ){
			$scope.editedProject.uID.push( p.users[key]["ID"] );
		}

		var param = {
			action : "update",
			current : p,
			editedProject : $scope.editedProject
		}

		openModal( param );
	}

	$scope.delete = function( ID ){

		bootbox.confirm("Are you sure to delete?", function(result) {
			
			if( result ){
				server.deleteProject(ID).then( function( response ){

					if( response.status ){
						$scope.projects = utils.pop( $scope.projects, response.data['deletedId'][0] );
					}

					utils.toast( response.message );
				}, onError );
			}

		}); 	
	}

	$scope.sort = function( key ){
		$scope.sortKey = key;
		$scope.reverse = !$scope.reverse;
	}

});

app.controller( "projectFormCtrl",function( $scope, $location, $uibModalInstance, server, utils, items ) {

	$scope.statusOption = [
		{"name":"On Going","value":"ongoing"},
		{"name":"Completed","value":"completed"},
		{"name":"Rejected","value":"rejected"},
		{"name":"Postponed","value":"postponed"}
	];

	$scope.editedProject = {};
	$scope.editedProject.status = "ongoing";

	server.getEstimation().then( function( response ){

		if( response.status ){
			$scope.estimations = response.data;
		}else{
			utils.toast( response.message );
		}

	});

	server.getUser().then( function( response ){

		if( response.status ){
			$scope.users = response.data;
		}else{
			utils.toast( response.message );
		}
	});

	server.getRole().then( function( response ){

		if( response.status ){
			$scope.roles = response.data;
		}else{
			utils.toast( response.message );
		}
	});
	
	if( items.action == "update" ){
		$scope.editedProject = items.editedProject;
		$scope.submitBtn = "Update";
		$scope.editedProject.seeEstimation = true;

	}else{
		$scope.submitBtn = "Save";
	}

	var onError = function( reason ){
		$location.path( '/error' );
		utils.toast( reason.statusText );
	}


	$scope.cancel = function () {
		$uibModalInstance.dismiss('cancel');
	};

	$scope.seeEstimation = function( eID ){

		$location.path( "/estimation/"+eID );
		$uibModalInstance.dismiss( 'cancel' );
	}

	$scope.submitForm = function( data ){

		server.saveProject( data ).then( function( response ){
			
			if(response.status){
				if( items.action == "save" ){
					items.projects.splice( 0,0,response.data[0] );
					$scope.editedProject = {};
				}

				if( items.action == "update" ){
					
					$scope.current = items.current;
					$scope.current.name = response.data[0].name;
					$scope.current.description = response.data[0].description;
					$scope.current.started_date = response.data[0].started_date;
					$scope.current.completed_date = response.data[0].completed_date;
					$scope.current.eID = response.data[0].eID;
					$scope.current.estimation = response.data[0].estimation;
					$scope.current.status = response.data[0].status;
					$scope.current.users = [];
					for( key in data.uID ){
						var temp = { "ID" : data.uID[key] };
						$scope.current.users.push( temp );
					}
					$uibModalInstance.dismiss('cancel');

				}
			}

			utils.toast( response.message );

		},onError );

	}
});

app.controller( "viewProjectCtrl", function( $scope,$filter, server, utils, loggedInUser, $routeParams ){

	
	utils.setTitle( "View Project || Project Management" );
	$scope.loggedInUser = loggedInUser,
	$scope.activeMenu = "Project";

	$scope.project = {};

	var ID = $routeParams.ID;
	var onError = function( reason ){

	}
	server.getProject( ID ).then( function( response ){

		if( response.status ){
			$scope.project = response.data[0];
		}else{
			utils.toast( response.message );
		}

	}, onError );

	
});