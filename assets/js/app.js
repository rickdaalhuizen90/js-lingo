'use strict';

window.addEventListener('load', function(){
	// Load content only on home page.
	if(window.location.pathname == '/lingo' || window.location.pathname == '/home'){

		// Shows preloader before page is loaded.
		setTimeout(function(){
			document.getElementById('preloader').style.display = "none";
		}, 800);

		// Get JWT
		var token = {

			// JWT
			parseJwt: function(token){
				var base64Url 	= token.split('.')[1];
	        	var base64 		= base64Url.replace('-', '+').replace('_', '/');
	        	return JSON.parse(window.atob(base64));
			}
		}

		// Get cookies
		var cookies = {
			
			//See: https://www.w3schools.com/js/js_cookies.asp
	   		getCookie: function(cname) {
			    var name = cname + "=";
			    var decodedCookie = decodeURIComponent(document.cookie);
			    var ca = decodedCookie.split(';');
			    for(var i = 0; i < ca.length; i++) {
			        var c = ca[i];
			        while (c.charAt(0) == ' ') {
			            c = c.substring(1);
			        }
			        if (c.indexOf(name) == 0) {
			            var cookie = c.substring(name.length, c.length);
			            var json = JSON.parse(cookie);

			            return json;
			        }
			    }

			    return "";
			}
	   	}

		// Sidebar object
		var sidebar = {

			// Toggle sidebar
			toggle: function(){
				var sideBar = document.getElementById("side_bar");
				var openNav = document.getElementById("open_nav");
				var closeNav = document.getElementById("close_nav");

				closeNav.addEventListener("click", function(){
					sideBar.style.width = "0";
				});

				openNav.addEventListener("click", function(){
					sideBar.style.width = "250px";
				})
			}
		}

		sidebar.toggle();

		// User object
		var user = {

			// Profile
		    profile: function () {
		    	var obj = token.parseJwt(document.cookie);
		        return {
		        	id: 	obj.id,
		        	name: 	obj.name, 
		        	avatar: obj.img,
		        	email: 	obj.email,
		        	roles:  obj.scopes,
		        	expire: obj.exp
		        };
		    },

		  	// Show user profile in sidebar
		  	renderToPage: function(){
		  		document.getElementById('avatar').style.backgroundImage = "url(" + this.profile().avatar + ")";
				document.getElementById('username').innerHTML = "Player: " + this.profile().name;
		  	}
		}

		user.renderToPage();

		// Show player scores
		var scoreboard = {

			playerOne: function(){
				var obj = user.profile();
				return {
					name: obj.name,
					score: 2800
				}
			},

			playerTwo: function(){
				// Check if cookie is defined
				if(cookies.getCookie("game_config").player_two) {
					var name = cookies.getCookie("game_config").player_two;
				} else {
					var name = "player_two";
				}

				return {
					name: name.replace("+", " "),
					score: 1500
				}
			},

			// Show player scores on page
			renderToPage: function(){
				var playerOneId = document.getElementById("player_one_score");
				var playerTwoId = document.getElementById("player_two_score");

				playerOneId.innerHTML = this.playerOne().name + ": " + this.playerOne().score;
				playerTwoId.innerHTML = this.playerTwo().name + ": " + this.playerTwo().score;
			}
		}

		scoreboard.renderToPage();

		// Pop-up for when the game is loaded
		var popUpModel = {

			content: document.getElementById("pop_up_model"),
			play_btn: document.getElementsByClassName("play_game"),

			render: function(){
				var model_content = this.content;
				var play = this.play_btn;

				if(localStorage["done"]){
					model_content.style.display = "none";
				} else {	
					model_content.style.display = "block";
				}

				play[0].addEventListener('click',  function(){
					localStorage['done'] = true;
					model_content.style.display = "none";
				});
			},

		    // Toggle Content 
		    toggle: function(){
		    	// Buttons
		    	var sp_btn = document.getElementById("single_player_button");
		    	
		    	// Content
		    	var sp = document.getElementById("single_player_content");
		    	sp.style.display = "none";

		    	// Button event for sinle player btn
		    	sp_btn.addEventListener('click', function(){ 
		    	
		    		if(sp.style.display == "none"){
		    			sp.style.display = "block";
		    		} else {
		    			sp.style.display = "none";
		    		}
		    	});
		    },
		}

	   	popUpModel.render();
	   	popUpModel.toggle();

		var roomOptions = {

			// Generate a random string(room_name)
		    getRoomName: function() {
			    return cookies.getCookie("game_config").room_name;
			},

		    renderRoomName: function(){
		    	document.getElementById("room_name").innerHTML = "Room Name: " + this.getRoomName();
		    }
		}

		roomOptions.renderRoomName();

		var singlePlayer = {

		}

		var logout = {

			//Empty local storage
			purgeLocalStorage: function(){
				var logout_btn = document.getElementById("logout");
				logout_btn.addEventListener('click',  function(){
					localStorage.removeItem("done");
				});
			},
		}

		logout.purgeLocalStorage();
	}
});
