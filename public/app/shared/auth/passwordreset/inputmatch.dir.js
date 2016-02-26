(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('compareTo', compareTo);

  	 /** @ngInject */
	function compareTo($compile) {

		function linkMethod(scope, element, attrs, ngModel) {

			ngModel.$validators.compareTo = function(modelValue) {
				return modelValue == scope.otherModelValue;
			};

			scope.$watch("otherModelValue", function() {
				ngModel.$validate();
			});
		}
		
		return {
			restrict: 'AE',
			require: "^ngModel",
			scope: {
				otherModelValue: "=compareTo"
			},
			replace: true,
			link: linkMethod
		}

	}

}());