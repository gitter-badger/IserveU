(function() {
	
	angular
		.module('iserveu')
		.factory('motionFilesFactory', ['$http', 'motionfile',
			motionFilesFactory]);

	function motionFilesFactory($http, motionfile) {

		var factory = {
			data: null,
			attach: function(id, files) {
                if (!files)
					return 0;

				for (var i in files)
					$http.post('api/motionfile/flowUpload', {
						motion_id: id,
						file_id: files[i]
					}).success(function(r){
						console.log(r);
					}).error(function(e){
						console.log(e);
					});
			},
			get: function(id) {
				motionfile.getMotionFiles(id).then(function(r){
					factory.data = r[0] ? r : null;
					return r;
				});
			},
			validate: function(file) {
	            if(!!{png:1,gif:1,jpg:1,jpeg:1,pdf:1}[file.getExtension()]) {
	                this.viewFiles.push(file);
	                this.upload(file);
	            } else {
	                this.uploadError = true;
	                this.errorFiles.push({file:file, error: "File must be a png, jpeg, gif, jpg, or pdf."});
	            }
			}
		}

		return factory;
	}


})();

