app.controller( 'loginCtrl',function( $scope, $rootScope, $location, $log, server, utils ){

	//Checking if User is already Logged In.
	server.getLoginStatus().then(function( response ){
		
		if( response.status ){
			// Redirect to dashboard if user is already logged In.
			$location.path( '/dashboard' );
		}

	},function( reason ){
		utils.redirectToErrorPage();
	});

	utils.setTitle( 'Login | Project Management' );

	var onLogin = function( response ){

		if( response.status == true ){
			$rootScope.user = response.data;
			$rootScope.loggedIn = true;
			$location.url('/dashboard');

		}else{
			utils.toast( "Incorrect Username or Password." );
		}
	}

	var onError = function( reason ){
			utils.toast( "Server Error." );
	}

	$scope.submit = function( user ){
		server.login( user ).then( onLogin, onError );
	}

});