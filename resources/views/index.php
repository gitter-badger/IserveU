<!doctype html>
<html ng-app="iserveu">
    <head>
        <title>IserveU <?=(config('app.sitename'))!=""?" - ".config('app.sitename'):""?></title>
        <meta name="viewport" content="initial-scale=1" />  
        <link rel="stylesheet" href="<?=elixir('css/all.css')?>">
        <link rel="stylesheet" href="/themes/<?=config('app.themename')?>/theme.css">
        <link rel="icon shortcut" type="image/png" href="/themes/<?=config('app.themename')?>/logo/symbol.png">                
    </head>
   <!--  <body class="background-image" style="background-image:url(/themes/default/photos/background.png)"  layout="row" >  -->
 <body back-img class="background-image" layout="row"/>

  <!--   /uploads/background_images/{{settings.image}} -->


        <md-sidenav class="site-sidenav md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')" ng-if="userIsLoggedIn">
            <sidebar sidebar="{{currentState}}">
                <div id="sidebar-inner"></div>
            </sidebar>
        </md-sidenav>

        <div layout="column" tabIndex="-1" role="main" flex>
          <md-toolbar class="md-toolbar-tools site-content-toolbar" ng-if="userIsLoggedIn">
            <div ng-controller="UserbarController as user" layout="column" class="md-toolbar-tools" ng-click="openMenu()" tabIndex="-1">
                <md-button class="md-icon-button" ng-click="user.toggleSidebar('left')" hide-gt-sm aria-label="Toggle Menu">
                  <md-icon class="mdi" md-font-icon="mdi-menu"></md-icon>
                </md-button>
                <div flex>
                    <h2 ng-cloak>{{user.userbarservice.title}}</h2>
                    <span flex></span> <!-- use up the empty space -->
                 </div>

                <div >
                    <div class="md-toolbar-item docs-tools" layout="column" layout-gt-md="row">
   

                        <md-menu md-position-mode="target-right target" ng-cloak>
                            <md-button aria-label="User Menu" class="md-icon-button" ng-click="$mdOpenMenu()">
                                <md-icon class="mdi" md-menu-origin md-font-icon="mdi-settings" ></md-icon>
                            </md-button>
                            <md-menu-content width="4">
                                <md-menu-item>
                                    <md-button ui-sref="login" ng-click="user.logout()">
                                        <p ng-cloak>Logout {{authenticatedUser.first_name}}</p>
                                        <md-icon class="mdi" md-font-icon="mdi-logout"></md-icon>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ng-if="showUsers" ui-sref="user.profile({id:1})">
                                    <md-button ng-click="user.showUserSideBar()">
                                        <p ng-cloak>User List</p>
                                        <md-icon class="mdi"  md-font-icon="mdi-account-multiple"></md-icon>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ng-if="createMotion" ui-sref="backgroundimage">
                                    <md-button>
                                        <p ng-cloak>Upload Background Image</p>
                                        <md-icon class="mdi"  md-font-icon="mdi-file-image"></md-icon>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ng-if="createMotion" ui-sref="department({id:1})">
                                    <md-button>
                                        <p ng-cloak>Department Manager</p>
                                        <md-icon class="mdi"  md-font-icon="mdi-folder-multiple-outline"></md-icon>
                                    </md-button>
                                </md-menu-item>
                                <md-menu-item ng-if="administrateProperties">
                                    <md-button>
                                        <p ng-cloak>Property Manager</p>
                                        <md-icon class="mdi"  md-font-icon="mdi-domain"></md-icon>
                                    </md-button>
                                </md-menu-item>
                            </md-menu-content>
                        </md-menu>
                    </div>
                </div>
            </div>
          </md-toolbar>

          <div flex ui-view></div>
          
          <md-caption layout-padding  class="imagecredit" ng-controller="BackgroundImageController as background">
            Photo courtesy of <a href="{{backgroundcredits.url}}">{{backgroundcredits.credited}} Photography</a>
          </md-caption>
          

        </div>
    </body>        

    <script src="<?=elixir('js/all.js')?>"></script>

        <script>
            angular.module("iserveu").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
        </script>
    <script src="/themes/<?=config('app.themename')?>/theme.js"></script>
</html> 
