app.controller( "dbMigrateCtrl", function( $scope, utils, loggedInUser ){

	utils.setTitle( 'DbMigrate | Project Management' );
	$scope.page = "DB Migrate";
	$scope.addBtn = false;
	$scope.loggedInUser = loggedInUser;
	$scope.code = false;
	$scope.activeMenu = "DB Migrate";

	console.log( loggedInUser );

	$scope.submit = function( db ){
		var query = "UPDATE "+db.prefix+"_options SET option_value = replace(option_value, '"+db.find+"', '"+db.replace+"') WHERE option_name = 'home' OR option_name = 'siteurl';";
		query += "\nUPDATE "+db.prefix+"_posts SET guid = replace(guid, '"+db.find+"','"+db.replace+"');";
		query += "\nUPDATE "+db.prefix+"_posts SET post_content = replace(post_content, '"+db.find+"', '"+db.replace+"');";
		query += "\nUPDATE "+db.prefix+"_postmeta SET meta_value = replace(meta_value,'"+db.find+"','"+db.replace+"');";

		$scope.code = query;
	}

	$scope.copy =  function(){
		new Clipboard('.copy');
		utils.toast( "Copied to Clipboard.")
	}

});