(function(){

    var module = {
        name: 'iserveu.motion',
        dependencies: [],
        config: {
            providers: ['$stateProvider', '$urlRouterProvider','$stateParams','$sce']
        },
        motionController: {
            name: 'MotionController',
            injectables: ['$rootScope', '$scope', '$stateParams', 'auth', 'motion', 'comment', '$mdToast', '$animate']
        }
    };        

    var MotionConfig = function($stateProvider) {
        $stateProvider
            .state( 'app.motion', {
                url: '/motion/:id',
                templateUrl: 'app/components/motion/motion.tpl.html',
                controller: module.motionController.name + ' as motion',
                data: {
                    requireLogin: true
                }
            });
    };

    MotionConfig.$provide = module.config.providers;

    var MotionController = function($rootScope, $scope, $stateParams, auth, motion, comment, $mdToast, $animate) {

        var vm = this;

    	vm.motionDetail = [];
        vm.motionComments = [];
        vm.motionVotes = {};
        vm.usersVote;
  /*      vm.voteFor; //Are these being used?
        vm.voteAgainst;
        vm.voteNeutral; 
        vm.commenttext;
        vm.first_name;
        vm.last_name;
        vm.email; 
        vm.agreeVoting = false;
        vm.abstainVoting = false;
        vm.disagreeVoting = false;*/

        function getMotion(id) {
            motion.getMotion(id).then(function(result) {
                vm.motionDetail = result;

                var disagree = {};
                var agree = {};
                var abstain = {};
                
                var totalVotes = 0;

                angular.forEach(result.votes, function(value, key) {

                    totalVotes += parseInt(value.count);
                    if(parseInt(value.position)==-1){
                       disagree.number = parseInt(value.count);
                    } else if(parseInt(value.position)==1){
                        agree.number = parseInt(value.count);
                    } else {
                        abstain.number = parseInt(value.count);
                    }
                });

                disagree.percentage =  (disagree.number/totalVotes)*100;
                agree.percentage =  (agree.number/totalVotes)*100;
                abstain.percentage =  (abstain.number/totalVotes)*100;

                disagree.roundedPercentage = Math.floor(disagree.percentage);
                agree.roundedPercentage = Math.floor(agree.percentage);
                abstain.roundedPercentage = Math.floor(abstain.percentage);

                disagree.remainder  = disagree.percentage % 1;
                agree.remainder  = agree.percentage % 1;
                abstain.remainder  = abstain.percentage % 1;

                if(disagree.remainder>Math.max(agree.remainder,abstain.remainder)){
                    disagree.roundedPercentage++;
                } else if(agree.remainder>abstain.remainder){
                    agree.roundedPercentage++;
                } else {
                    abstain.roundedPercentage++;
                }

                vm.motionVotes.disagree = disagree;
                vm.motionVotes.agree = agree;
                vm.motionVotes.abstain = abstain;


            });            
        }

        function getMotionComments(id) {
            motion.getMotionComments(id).then(function(result) {
                vm.motionComments = result;
                console.log(vm.motionComments);
            });
        }

        function getLoggedInUser() {
            auth.getLoggedInUser().then(function(result) {
                vm.loggedInUser = result;
                vm.first_name = result.first_name;
                vm.last_name = result.last_name;
                vm.email = result.email;
            },function(error){
                // a 404 error
            });        
        }

        vm.castVote = function(position) {
            var message = "You";
            switch(position) {
                case -1: 
                    message = message+" disagree with this motion";
                    vm.disagreeVoting = true;
                    break;
                case 1:
                    message = message+" agreed with this motion";
                    vm.agreeVoting = true;
                    break;
                default:
                    message = message+" abstained from voting on this motion";
                    vm.abstainVoting = true;
            }

            var data = {
                motion_id:$stateParams.id,
                position:position,
                message:message
            }

            motion.castVote(data).then(function(result) {
                getUsersVotes();
                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;
                $mdToast.show(
                  $mdToast.simple()
                    .content(message)
                    .position('bottom right')
                    .hideDelay(3000)
                );
                console.log(result);
            }, function(error) {
                vm.agreeVoting = false;
                vm.abstainVoting = false;
                vm.disagreeVoting = false;
                console.log(error);
            });

            $scope.$emit('voteCast', {position:position, id:$stateParams.id});

        }

        vm.calculateVotes = function(){

        }

        vm.submitComment = function(text) {
            var data = {
                motion_id:$stateParams.id,
                approved: 0,
                text: text
            }
            comment.saveComment(data).then(function(result) {
                vm.commenttext = '';
                getMotionComments($stateParams.id);
                console.log(result);
                
            }, function(error) {
                console.log(error);
            });            
        }

        vm.updateComment = function(data) {
            comment.updateComment(data).then(function(result) {
                getMotionComments($stateParams.id);
            }, function(error) {

            });
        }

        vm.deleteComment = function(id) {
            comment.deleteComment(id).then(function(result) {
                getMotionComments($stateParams.id);
            }, function(error) {
                
            });           
        }

        function getUsersVotes() {
            motion.getUsersVotes().then(function(result) {
                angular.forEach(result, function(value, key) {
                    if(value.motion_id === $stateParams.id) {
                        vm.usersVote = parseInt(value.position);
                    }
                });
            });
        }        

        getMotion($stateParams.id);
        getMotionComments($stateParams.id);
        getLoggedInUser();
        getUsersVotes();

    };

    MotionController.$inject = module.motionController.injectables;

    angular.module(module.name, module.dependencies)
        .config(MotionConfig)
        .controller(module.motionController.name, MotionController);

}());