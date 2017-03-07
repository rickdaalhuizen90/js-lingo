'use strict';

window.addEventListener('load', function(){

	// Shows preloader before page is loaded.
	function preload(ms){
		setTimeout(function(){
			document.getElementById('preloader').style.display = "none";
		}, ms)
	}

	//preload(1500);

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

   	var logout = {

		//Empty local storage
		purgeLocalStorage: function(){
			var logout_btn = document.getElementById("logout");
			logout_btn.addEventListener('click',  function(){
				localStorage.removeItem("done");
			});
		},

		action: function(){
			this.purgeLocalStorage();
		}
	}

	logout.action();

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
		},

		// User profile
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
	  	render: function(){
	  		var obj = cookies.getCookie("game_config");
	  		document.getElementById('avatar').style.backgroundImage = "url(" + this.profile().avatar + ")";
			document.getElementById('username').innerHTML = "Player: " + this.profile().name;
			document.getElementById("room_name").innerHTML = "Room Name: " + obj.room_name;
	  	}
	}

	sidebar.toggle();
	sidebar.render();

	// Pop-up for when the game is loaded
	var popUpModel = {

		content: document.getElementById("pop_up_model"),
		play_btn: document.getElementsByClassName("play_game"),

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
			});
		},
	}

   	popUpModel.toggle();
   	popUpModel.render();

	// Show player scores
	var scoreboard = {

		gameObject:  function(){
			return cookies.getCookie("game_current");
		},

		// Show player lives on page
		lives: function(){
			var lives = document.getElementById("lives");
			var obj = this.gameObject();

			lives.innerHTML = "Lives: " + obj.lives;
		},

		// Show timer on page
		time: function(){
		    var el = document.getElementById("time");
		    var counter = 120;
		    var minus = "";
		    
		    el.innerHTML = "Time: " + counter;

		    var interval = setInterval(function(){
		      el.innerHTML = "Time: " + minus + counter--;

		      	if(counter < 10) minus = "0";
		      	if(counter < 0){
		        	clearInterval(interval);
		        	lingo.gameOver(el);

		     	}
		    }, 1000);
		},

		// Show player score on page
		score: function(){
			var score = document.getElementById("score");
			score.innerHTML = "Score: 280";
		},

		render: function(){
			this.score();
			this.time();
			this.lives();
		}
	}

	scoreboard.render();

	var lingo = {

		gameObject: function(){
			console.log(cookies.getCookie("game_current"));
			return cookies.getCookie("game_current");
		},

		startGame: function(){
			
			if(localStorage['done'] = true){
				var randomWord = this.randomWord();
				console.log(randomWord);
			};

			// initialize user's input to submit a word
			// 
		},

		randomWord: function(){
			// Get random word
			return this.gameObject().word;
		},

		// Game over
		gameOver: function(){
			var el = document.getElementById("time");
			el.innerHTML = "Game over";
			// Stop game
			// Show game over on screen.
			// save score (see scoreboard)
		}

		// word correct
		// word incorrect
	}

	lingo.startGame();
});
