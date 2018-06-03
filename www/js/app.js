angular.module("apps_promo", ["ngCordova","ionic","ionMdInput","ionic-material","ion-datetime-picker","ionic.rating","utf8-base64","angular-md5","chart.js","pascalprecht.translate","tmh.dynamicLocale","apps_promo.controllers", "apps_promo.services"])
	.run(function($ionicPlatform,$window,$interval,$timeout,$ionicHistory,$ionicPopup,$state,$rootScope){

		$rootScope.appName = "Apps Promo" ;
		$rootScope.appLogo = "data/images/header/mylogo.png" ;
		$rootScope.appVersion = "1.0.2" ;
		$rootScope.headerShrink = false ;

		$ionicPlatform.ready(function() {

			localforage.config({
				driver : [localforage.WEBSQL,localforage.INDEXEDDB,localforage.LOCALSTORAGE],
				name : "apps_promo",
				storeName : "apps_promo",
				description : "The offline datastore for Apps Promo app"
			});

			if(window.cordova){
				$rootScope.exist_cordova = true ;
			}else{
				$rootScope.exist_cordova = false ;
			}
			//required: cordova plugin add ionic-plugin-keyboard --save
			if(window.cordova && window.cordova.plugins.Keyboard) {
				cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
				cordova.plugins.Keyboard.disableScroll(true);
			}

			//required: cordova plugin add cordova-plugin-statusbar --save
			if(window.StatusBar) {
				StatusBar.styleDefault();
			}
			// this will create a banner on startup
			//required: cordova plugin add cordova-plugin-admob-free --save
			if (typeof admob !== "undefined"){
				var admobid = {};
				admobid = {
					banner: "ca-app-pub-9424912518456288/9975508313",
					interstitial: "ca-app-pub-9424912518456288/9919667996",
					rewardvideo: ""
				};
				// banner
				admob.banner.config({
					id: admobid.banner,
				});
				admob.banner.prepare();
				$timeout(function(){
					admob.banner.show();
				},1000); 
				// interstitial
				admob.interstitial.config({
					id: admobid.interstitial,
				});
				admob.interstitial.prepare();
				$timeout(function(){
					admob.interstitial.show();
				},1000); 
				// rewardvideo
				admob.rewardvideo.config({
					id: admobid.rewardvideo,
				});
			}


			//required: cordova plugin add onesignal-cordova-plugin --save
			if(window.plugins && window.plugins.OneSignal){
				window.plugins.OneSignal.enableNotificationsWhenActive(true);
				var notificationOpenedCallback = function(jsonData){
					try {
						$timeout(function(){
							$window.location = "#/apps_promo/" + jsonData.notification.payload.additionalData.page ;
						},200);
					} catch(e){
						console.log("onesignal:" + e);
					}
				}
				window.plugins.OneSignal.startInit("d9c45214-d2dd-4740-b32c-b9f1aacca3ca").handleNotificationOpened(notificationOpenedCallback).endInit();
			}


		});
		$ionicPlatform.registerBackButtonAction(function (e){
			if($ionicHistory.backView()){
				$ionicHistory.goBack();
			}else{
				var confirmPopup = $ionicPopup.confirm({
					title: "Confirm Exit",
					template: "Are you sure you want to exit?"
				});
				confirmPopup.then(function (close){
					if(close){
						ionic.Platform.exitApp();
					}
				});
			}
			e.preventDefault();
			return false;
		},101);
	})


	.filter("to_trusted", ["$sce", function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])

	.filter("trustUrl", function($sce) {
		return function(url) {
			return $sce.trustAsResourceUrl(url);
		};
	})

	.filter("trustJs", ["$sce", function($sce){
		return function(text) {
			return $sce.trustAsJs(text);
		};
	}])

	.filter("strExplode", function() {
		return function($string,$delimiter) {
			if(!$string.length ) return;
			var $_delimiter = $delimiter || "|";
			return $string.split($_delimiter);
		};
	})

	.filter("strDate", function(){
		return function (input) {
			return new Date(input);
		}
	})
	.filter("phpTime", function(){
		return function (input) {
			var timeStamp = parseInt(input) * 1000;
			return timeStamp ;
		}
	})
	.filter("strHTML", ["$sce", function($sce){
		return function(text) {
			return $sce.trustAsHtml(text);
		};
	}])
	.filter("strEscape",function(){
		return window.encodeURIComponent;
	})
	.filter("strUnscape", ["$sce", function($sce) {
		var div = document.createElement("div");
		return function(text) {
			div.innerHTML = text;
			return $sce.trustAsHtml(div.textContent);
		};
	}])

	.filter("stripTags", ["$sce", function($sce){
		return function(text) {
			return text.replace(/(<([^>]+)>)/ig,"");
		};
	}])

	.filter("chartData", function(){
		return function (obj) {
			var new_items = [];
			angular.forEach(obj, function(child) {
				var new_item = [];
				var indeks = 0;
				angular.forEach(child, function(v){
						if ((indeks !== 0) && (indeks !== 1)){
							new_item.push(v);
						}
						indeks++;
					});
					new_items.push(new_item);
				});
			return new_items;
		}
	})

	.filter("chartLabels", function(){
		return function (obj){
			var new_item = [];
			angular.forEach(obj, function(child) {
			var indeks = 0;
			new_item = [];
			angular.forEach(child, function(v,l) {
				if ((indeks !== 0) && (indeks !== 1)) {
					new_item.push(l);
				}
				indeks++;
			});
			});
			return new_item;
		}
	})
	.filter("chartSeries", function(){
		return function (obj) {
			var new_items = [];
			angular.forEach(obj, function(child) {
				var new_item = [];
				var indeks = 0;
				angular.forEach(child, function(v){
						if (indeks === 1){
							new_item.push(v);
						}
						indeks++;
					});
					new_items.push(new_item);
				});
			return new_items;
		}
	})



.config(["$translateProvider", function ($translateProvider){
	$translateProvider.preferredLanguage("id-id");
	$translateProvider.useStaticFilesLoader({
		prefix: "translations/",
		suffix: ".json"
	});
	$translateProvider.useSanitizeValueStrategy("escapeParameters");
}])


.config(function(tmhDynamicLocaleProvider){
	tmhDynamicLocaleProvider.localeLocationPattern("lib/ionic/js/i18n/angular-locale_{{locale}}.js");
	tmhDynamicLocaleProvider.defaultLocale("id-id");
})


.config(function($stateProvider, $urlRouterProvider,$sceDelegateProvider,$httpProvider,$ionicConfigProvider){
	try{
		// Domain Whitelist
		$sceDelegateProvider.resourceUrlWhitelist([
			"self",
			new RegExp('^(http[s]?):\/\/(w{3}.)?youtube\.com/.+$'),
			new RegExp('^(http[s]?):\/\/(w{3}.)?w3schools\.com/.+$'),
		]);
	}catch(err){
		console.log("%cerror: %cdomain whitelist","color:blue;font-size:16px;","color:red;font-size:16px;");
	}
	$stateProvider
	.state("apps_promo",{
		url: "/apps_promo",
			abstract: true,
			templateUrl: "templates/apps_promo-side_menus.html",
			controller: "side_menusCtrl",
	})

	.state("apps_promo.about_us", {
		url: "/about_us",
		cache:false,
		views: {
			"apps_promo-side_menus" : {
						templateUrl:"templates/apps_promo-about_us.html",
						controller: "about_usCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("apps_promo.aplikasi_gratis_singles", {
		url: "/aplikasi_gratis_singles/:id",
		cache:false,
		views: {
			"apps_promo-side_menus" : {
						templateUrl:"templates/apps_promo-aplikasi_gratis_singles.html",
						controller: "aplikasi_gratis_singlesCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("apps_promo.dashboard", {
		url: "/dashboard",
		cache:false,
		views: {
			"apps_promo-side_menus" : {
						templateUrl:"templates/apps_promo-dashboard.html",
						controller: "dashboardCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	.state("apps_promo.faqs", {
		url: "/faqs",
		views: {
			"apps_promo-side_menus" : {
						templateUrl:"templates/apps_promo-faqs.html",
						controller: "faqsCtrl"
					},
			"fabButtonUp" : {
						template: '',
					},
		}
	})

	$urlRouterProvider.otherwise("/apps_promo/dashboard");
});
