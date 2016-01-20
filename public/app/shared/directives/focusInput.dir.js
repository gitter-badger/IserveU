(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('focusInput', focusInput);

	function focusInput($timeout) {

		console.log('foo');

	    return {
	        restrict : 'A',
	        link : function($scope,$element,$attr) {
	            $scope.$watch ($attr.focusInput,function(_focusVal) {
	            	console.log(_focusVal);
	                $timeout(function() {
	                    _focusVal ? $element.focus() :
	                        $element.blur();
	                });
	            });
	        }
	    }


	}

})();