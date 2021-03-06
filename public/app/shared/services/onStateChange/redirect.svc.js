(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('redirect', ['$rootScope', '$state', redirect]);

	/** @ngInject */
	function redirect($rootScope, $state) {

		/**
		*	Redirect function for when a user is forwarded to a site URL and
		*	logs in. They will be redirected the previous state they were
		*	at before being rejected by authentication.
		*/
		this.onLogin = function(state, params, prevState) {
			if(state.name !== 'login')
				if (state.name !== 'login.resetpassword') {
				$rootScope.redirectUrlName = state.name;
				$rootScope.redirectUrlID = params.id;
				$rootScope.previousUrlID = prevState.id;
			};
		};
	
		/**
		*	Redirect when a user is not authenticated via AuthController
		*	or they have somehow lost their localstorage credentials. Logs the user
		*	out and redirects them to login state.
		*/
		this.ifNotAuthenticated = function(ev, requireLogin, auth, state, prevState) {
			if(auth === false && requireLogin === true){
				ev.preventDefault();
				if(prevState !== 'login' || state !== 'login')
					$state.go('login');
			};
		}; 



	}

})();