(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('userFab', [
			'$stateParams', 'user', 'ToastMessage', 'fabLink',
			userFab]);

	/** @ngInject */
	function userFab($stateParams, user, ToastMessage, fabLink) {

		function userFabController() {
			
			this.isOpen = false;

			this.user_id = $stateParams.id;

			this.destroy = function() {

				ToastMessage.destroyThis("user", function(){
					user.deleteUser($stateParams.id);
				});
			
			};


		}

		function userFabLink(scope, el, attrs) {
			fabLink(el);
		}

		return {
			controller: userFabController,
			controllerAs: 'fab',
			link: userFabLink,
			templateUrl: 'app/components/user/components/user-fab/user-fab.tpl.html'
		}

	}


})();