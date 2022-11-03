app.controller( "estimationCtrl",function( $uibModal, $location, $scope, $routeParams, server, utils, loggedInUser){

	utils.setTitle( "Estimation || Project Management" );
	$scope.page = "Estimation",
	$scope.addBtn = true,
	$scope.loggedInUser = loggedInUser,
	$scope.activeMenu = 'Estimations',
	$scope.fromYear = (utils.getCurrentYear()).toString(),
	$scope.toYear = utils.getCurrentYear().toString(),
	$scope.pagingConfig = {};

	utils.pagingConfig().then(function( response ){
		$scope.pagingConfig = response;
	});

	server.getEstimationYears().then( function( response ){
		if( response.status ){
			$scope.years = response.data;
		}
	});

	var routeParam = $routeParams.action;

	var onError = function( reason ){
		$location.path( "/error" );
		utils.toast( reason.statusText );
	}

	var onGetEstimation = function( response ){

		if( response.status ){
			$scope.estimations = response.data;

		}else{
			$scope.estimations = [];
			utils.toast( response.message );
		}
	}

	var openModal = function( param ){
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'estimationForm.html',
			controller: 'estimationFormCtrl',
			size: 'lg',
			resolve:  {
				items : function(){
					return param;
				}
			}
		});
	}

	$scope.editedEstimation = {};

	$scope.edit = function( e ){

		$scope.editedEstimation.name = e.name;
		$scope.editedEstimation.ID = e.ID;
		$scope.editedEstimation.description = e.description;
		$scope.editedEstimation.status = e.status;

		$scope.editedProject.uID = [];

		for( key in p.users ){
			$scope.editedProject.uID.push( p.users[key]["ID"] );
		}

		var param = {
			action  : "update",
			current : e,
			editedEstimation : $scope.editedEstimation,
		};

		openModal( param );
	}

	$scope.selectToggle = function(){
	    if( $scope.toggle ){
	        $scope.user.estimationIDs = [];
	        $scope.toggle = false;
	    }else{
	        $scope.user.estimationIDs = $scope.estimations.map(function(item) { return item.ID; });
	        $scope.toggle = true;   
	    }

	}

	if( routeParam > 0 ){
		setTimeout( function(){
			angular.element( "#click-"+routeParam ).trigger('dblclick');
		},500);
	}

	$scope.open = function () { 
		var param = {
			action : "save",
			estimations : $scope.estimations,
			editedEstimation : {}
		};

		openModal( param ); 		 
	};

	$scope.delete = function( data ){

		bootbox.confirm("Are you sure to delete?", function(result) {
			if(result){
				server.deleteEstimation( data.ID ).then( function( response ){
					if( response.status ){
						$scope.estimations = utils.pop( $scope.estimations, response.data["deletedId"][0] );
					}
					utils.toast( response.message );
				}, onError);
			}
		});

	}

	$scope.getData = function(){
		server.getEstimationBetweenYears( $scope.fromYear, $scope.toYear ).then( onGetEstimation, onError );
	}

	$scope.getData();

	$scope.dbSearch = function( key ){
		server.dbSearch( key, "estimation" ).then( onGetEstimation, onError );
	}

	$scope.sortKey = 'ID';
	$scope.reverse = true;
	
	$scope.sort = function( key ){
		$scope.sortKey = key;
		$scope.reverse = !$scope.reverse;
	}
});

app.controller( "estimationFormCtrl", function( $scope, $location, $uibModalInstance, utils, server, items ){

	$scope.editedEstimation = items.editedEstimation;
	
	if( items.action == "update" ){
		$scope.submitBtn = "Update";
	}else{
		$scope.submitBtn = "Save";
		$scope.editedEstimation.status = "pending";
	}

	$scope.statusOptions = [ 
		{ "name":"Accepted", "value":"accepted"},
		{ "name":"Rejected", "value":"rejected"},
		{ "name": "Pending", "value":"pending"}
	];

	var onError = function( reason ){

	}

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

	$scope.cancel = function () {
		$uibModalInstance.dismiss('cancel');
	};

	$scope.submitForm = function( data ){

		server.saveEstimation( data ).then( function( response ){

			var msg;

			if( response.status ){
				
				if( items.action == "save" ){

					$scope.estimations = items.estimations;
					items.estimations.splice(0,0,response.data);
					$scope.editedEstimation = {};

				}

				if( items.action == "update" ){
					$scope.current = items.current;

					$scope.current.name = response.data.name;
					$scope.current.status= response.data.status;
					$scope.current.description= response.data.description;
					// $location.path( "/estimation" );
					$uibModalInstance.dismiss('cancel');
				}

				msg = response.message;

			}else{
				msg = response.message;
			}

			utils.toast( msg );

		});
	}

});

app.controller( "viewEstimationCtrl", function( $scope, server, utils, loggedInUser, $routeParams ){
	
	utils.setTitle( "View Estimation || Project Management" );
	$scope.page = "Estimation";
	$scope.loggedInUser = loggedInUser;
	$scope.activeMenu = 'Estimation';

	$scope.estimation = {};

	var ID = $routeParams.ID;
	var onError = function( reason ){

	}

	server.getEstimation( ID ).then( function( response ){

		if( response.status ){
			$scope.estimation = response.data;
			server.getUser( response.data.user_id ).then( function( response ){
				$scope.estimatedBy = response.data;				
			})
		}else{
			utils.toast( response.message );
		}

	}, onError );
	
});