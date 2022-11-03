app.controller( "estimationEditCtrl",function( $uibModal, $location, $scope, $routeParams, server, utils, loggedInUser){

	utils.setTitle( "Estimation Edit || Project Management" );
	$scope.page = "Estimation",
	$scope.addBtn = true,
	$scope.loggedInUser = loggedInUser,
	$scope.activeMenu = 'Estimations';
	$scope.lists = [];

	$scope.pagingConfig = {};

	utils.pagingConfig().then(function( response ){
		$scope.pagingConfig = response;
	});

	var ID = $routeParams.ID;

	var onError = function( reason ){

	};

	var calculateEstimatedTime = function(){

		var temp = 0, hours = 0, minutes = 0;
		for( key in $scope.lists ){
			temp = parseInt( $scope.lists[key].hour );
			hours += isNaN( temp ) ? 0 : temp;

			temp = parseInt( $scope.lists[key].minute );
			minutes += isNaN( temp ) ? 0 : temp;
		}

		$scope.estimatedTime = { value: utils.secondsToHours( (hours * 60 * 60) + (minutes * 60) ) }
	}

	server.getEstimationList( ID ).then( function( response ){
		if( response.status ){
			$scope.lists = response.data;
			calculateEstimatedTime();
		}
	});

	//get estimated by
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

	var openModal = function( param ){
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'estimationListForm.html',
			controller: 'estimationListFormCtrl',
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
			lists   : $scope.lists,
			estimatedTime: $scope.estimatedTime,
			calculateEstimatedTime: calculateEstimatedTime,
			editedEstimation : { "estimation_id": ID }
		};

		openModal( param ); 		 
	};
	
	$scope.editedEstimation = {};
	$scope.edit = function( e ){

		$scope.editedEstimation.name = e.name;
		$scope.editedEstimation.ID = e.ID;
		$scope.editedEstimation.estimation_id = e.estimation_id;
		$scope.editedEstimation.description = e.description;
		$scope.editedEstimation.hour = parseInt( e.hour );
		$scope.editedEstimation.minute = parseInt( e.minute );

		var param = {
			action  : "update",
			current : e,
			lists   : $scope.lists,
			estimatedTime: $scope.estimatedTime,
			calculateEstimatedTime: calculateEstimatedTime,
			editedEstimation : $scope.editedEstimation,
		};

		openModal( param );
	}

	$scope.delete = function( data ){

		bootbox.confirm("Are you sure to delete?", function(result) {
			if(result){
				server.deleteEstimationList( data.ID ).then( function( response ){
					if( response.status ){
						$scope.lists = utils.pop( $scope.lists, response.data["deletedId"][0] );
						calculateEstimatedTime();
					}
					utils.toast( response.message );
				}, onError);
			}
		});

	}

	$scope.sortKey = 'ID';
	$scope.reverse = true;
	
	$scope.sort = function( key ){
		$scope.sortKey = key;
		$scope.reverse = !$scope.reverse;
	}
});

app.controller( "estimationListFormCtrl", function( $scope, $location, $uibModalInstance, utils, server, items ){

	$scope.editedEstimation = items.editedEstimation;
	$scope.estimatedTime = items.estimatedTime;
	$scope.lists = items.lists;

	if( items.action == "update" ){
		$scope.submitBtn = "Update";
	}else{
		$scope.submitBtn = "Save";
	}

	var onError = function( reason ){

	};

	$scope.cancel = function () {
		$uibModalInstance.dismiss('cancel');
	};

	$scope.submitForm = function( data ){ 
		server.saveEstimationList( data ).then( function( response ){

			var msg;

			if( response.status ){
				
				if( items.action == "save" ){

					items.lists.splice( 0, 0, response.data );
					$scope.editedEstimation = {estimation_id: response.data.estimation_id};

				}

				if( items.action == "update" ){
					$scope.current = items.current;

					$scope.current.name = response.data.name;
					$scope.current.hour= response.data.hour;
					$scope.current.minute= response.data.minute;
					$scope.current.description= response.data.description;
					$scope.current.estimation_id = response.data.estimation_id;
					$uibModalInstance.dismiss('cancel');
				}

				items.calculateEstimatedTime();

				msg = response.message;

			}else{
				msg = response.message;
			}

			utils.toast( msg );

		});
	}

});