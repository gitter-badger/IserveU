(function() {

'use strict';

angular
	.module('iserveu')
	.factory('motionIndex', 
		['$http',
		 'motionResource', 
	
	function($http, motionResource) {

	var motionIndex = {

		_index: {},

		_current_page: 1,

		_next_page: 2,

		_paginating: true,

		_load: function() {

			var self = this;

			if(self._index.length > 0)
				return false;

			self._paginating = true;

			motionResource.getMotionsIndex(this._current_page).then(function(results) {

				self._next_page = self.nextPage(results.next_page_url);
				self._index = results.data;
				self._paginating = false;

			}, function(error) {
				throw new Error('Unable to retrieve initial index of motions.');
			});
		},

		clear: function() {
			var originalLength = this._index.length;
			for (var i = originalLength; i > 0; i--) {
			     this._index.pop();
			}
		},

		loadMoreMotions: function() {

			var self = this;

			if(!self._next_page)
				return false;

			self._paginating = true;

			motionResource.getMotionsIndex(self._next_page).then(function(results) {

				self._next_page = self.nextPage(results.next_page_url);
				self._index = self._index.concat(results.data);
				self._paginating = false;

			}, function(error) {
				throw new Error('Unable to retrieve next page of motion index.');
			});

		},

		nextPage: function(url) {
			var self = this;
			return url ? url.slice(-1) : null;
		},

		retrieveById: function(id) {
			for(var i in this._index) {
				if( id == this._index[i].id )
					return this._index[i];
			}

			return false;
		},

		reloadOne: function(motion) {
			for(var i in this._index) {
				if( motion.id == this._index[i].id )
					this._index[i] = motion;
			}
			return true;
		}
	}

	return motionIndex;

}])


})();