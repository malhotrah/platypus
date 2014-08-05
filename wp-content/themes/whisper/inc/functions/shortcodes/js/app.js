// Controller for Client
FITSCApp.controller( 'Client', function ( $scope )
{
	$scope.counter = 1;
	$scope.blocks = [
		{id: 1, name: '', intro: '', image: '', url: ''}
	];
	$scope.add = function ()
	{
		$scope.counter++;
		$scope.blocks.push( {id: $scope.counter, name: '', intro: '', image: '', url: ''} );
	}
} );
