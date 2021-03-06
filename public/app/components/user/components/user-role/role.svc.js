(function() {


	'use strict';

	angular
		.module('iserveu')
		.factory('roleFactory', ['$stateParams', '$state', 'role', 'user', roleFactory]);

  	 /** @ngInject */
	function roleFactory($stateParams, $state, role, user){

		var factory = {
			list: {},
			editRole: false,
			user: [],
			showRoles: function() {
				this.showEdit = !this.showEdit;
			},
			check: function(d) {
				for(var i in d) 
				for(var j in this.user)
					if(d[i].display_name == this.user[j])
						this.list[i]['hasRole'] = true;
			},
			set: function(role) {
				this.setRole(role,$stateParams.id);
			},
			setRole: function(role, id) {
				if(!role.hasRole)
					this.grant(role, id);
				else if(role.hasRole)
					this.remove(role, id);
			},
			grant: function (r, id){
				role.grantRole({
					id: id,
					role_name: r.name}).then(function(){
						$state.reload();
				});
			},
			remove: function (r, id){
				role.deleteUserRole({
					id: id,
					role_id: r.id}).then(function(){
						$state.reload();
				});
			},
			getAllRoles: function() {
				role.getRoles().then(function(r){
					factory.list = r;
					factory.check(r);
				});
			}
		}

		factory.getAllRoles();

		return factory;

	}

})();