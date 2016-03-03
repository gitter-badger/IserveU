(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('dateService', dateService);


  	 /** @ngInject */
	function dateService($filter) {

		this.stringify = stringify;
		this.updateForPost = updateForPost;

		function stringify (date) {
			if( angular.isString(date) )
				return parse(date);
			return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
		};

		function parse (date) {
			return $filter('date')( (new Date(date)), "yyyy-MM-dd HH:mm:ss");
		};

		function updateForPost (date) {
			var tempDate = date;
			date = null;
			return stringify(date);
		};

	}


})();