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
			var toggle = document.getElementById("toggle_nav");

			toggle.addEventListener("click", function(){
				if(document.body.style.marginTop === "-500px"){
					document.body.style.marginTop = "0px";
				}else {
					document.body.style.marginTop = "-500px";
				}
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

		gameObject:  cookies.getCookie("game_current"),

		// Show player lives on page
		lives: function(){
			var lives = document.getElementById("lives");
			var obj = this.gameObject;

			lives.innerHTML = "Lives: " + obj.lives;
		},

		// Show timer on page
		time: function(minutes, seconds){
		    var el 	= document.getElementById("time");
		    el.innerHTML = "Time: 0" + minutes + ":00";

		     // set time for the particular countdown
		    var time = minutes * 60 + seconds;
		    var interval = setInterval(function(){
		        // if the time is 0 then end the counter
		        if (time <= 0) {
		           	clearInterval(interval);
		        	lingo.gameOver(el);
		        }

		        var minutes = Math.floor( time / 60 );
		        if (minutes < 10) minutes = "0" + minutes;
		        
		        var seconds = time % 60;
		        if (seconds < 10) seconds = "0" + seconds; 
		        	el.innerHTML = "Time: " + minutes + ':' + seconds; 
		        
		        time--;
		    }, 1000);
		},

		// Show player score on page
		score: function(){
			var score = document.getElementById("score");
			var obj = this.gameObject;

			score.innerHTML = "Score: " + obj.score;
		},

		render: function(){
			this.score();
			this.time(3,0);
			this.lives();
		}
	}

	scoreboard.render();

	var lingo = {

		gameObject: cookies.getCookie("game_current"),
		gameConfig: cookies.getCookie("game_config"),

		startGame: function(){
			
			if(localStorage['done'] = true){

				// Init random word
				var randomWord = this.gameObject.word;
				console.log("Random word: " + randomWord);

				// Create grid for playboard
				this.drawGrid();
				this.playboardForm();

				// On form submit
				this.run();
			};
		},

		drawGrid: function(){
			var grid 			= document.getElementById("playboard_grid");
			var row_count 		= this.gameConfig.game_mode;
			var column_count 	= this.gameConfig.game_mode;

			// Create rows
			for(var a = 1; a <= row_count; a++){
				var row = document.createElement("div");
				row.className = "playboard_row row_" + a;
				grid.appendChild(row);

				// Create columns
				for(var b = 0; b < column_count; b++){
					var column 	= document.createElement("div"); 
					column.className = "col col-4 playboard_column";

					document.getElementsByClassName("row_" + a)[0].appendChild(column);
				}
			}
		},

		playboardForm: function(){
			var form = document.getElementById("playboard_form");
			var max = this.gameConfig.game_mode;
			form.max = parseInt(max);
		},

		validateUserInput: function(){	
			var input_value = document.getElementById("playboard_form").value;
			var str_length 	= this.gameConfig.game_mode;
			var regex 		= /^[a-zA-Z]+$/;
			var errorMsg 	= document.getElementById("error_message");

			if(	input_value != "" && 
				input_value.length === str_length &&  
				input_value.match(regex)
			){
				return input_value;
			} else {
				errorMsg.style.display = "block";

				if(input_value.length !== str_length) {
					var msg = "Word should be " + str_length + " letters long!";
				} else {
					var msg = "Oops input value is not valid";
				}

				// Return error message.
				errorMsg.innerHTML = msg;
				
				setInterval(function(){
					errorMsg.style.display = "none";
					errorMsg.innerHTML = "";
				},5000);

				return false;
			}
		},

		splitWord: function(word){
			// Check if parameter is defined.
			if(word == null) return;

			var letters 	= word.split("");
			var splitted 	= [];

			for(var i = 0; i < letters.length; i++)
				splitted.push(letters[i]);

			return splitted;
		},

		insertLettersInGrid: function(row){

			var user_input 	= this.validateUserInput();
			var letters 	= this.splitWord(user_input);

			if(row < letters.length){
				var row_el 		= document.getElementsByClassName("playboard_row")[row];
				var column 		= row_el.getElementsByClassName("playboard_column");

				// Insert letters in the right row and columns.
				for(var i = 0; i < letters.length; i++)	
					column[i].innerHTML += "<p>" + letters[i] + "</p>";

				return true;
			}else{
				console.log("Game over!");
				document.getElementById("time").innerHTML = "Game over!";
			}

			return false;
		},

		checkWord: function(row){
			// Random word & input value
			var word 			= this.gameObject.word;
			var input_value 	= document.getElementById("playboard_form").value;
			
			// Splitted random word & input value
			var splitted_word 	= this.splitWord(word);
			var splitted_input  = this.splitWord(input_value);

			// Grid elements
			var row_el 		= document.getElementsByClassName("playboard_row")[row - 1];
			var column 		= row_el.getElementsByClassName("playboard_column");

			// Check if variables are set and true.
			if(splitted_word != null && splitted_input != null){

				// Debugging::::
				var obj = {
					input: splitted_input,
					word: splitted_word
				}
				console.log(obj);

				// Check if length matches.
				if(splitted_word.length === splitted_input.length){

					for(var i = 0; i < splitted_input.length; i++){
						
						setTimeout(function(i){    

							// Check if a letter match exsist and match the right position(returns -1 on false)
							if(	splitted_word.indexOf(splitted_input[i]) >= 0 &&
								splitted_word[i] === splitted_input[i]
							){
								
								column[i].getElementsByTagName("p")[0].style.backgroundColor = "#2bec7f";
								console.log(splitted_input[i] + "  exsist and match the right position");

							// Check if a letter exsists
							}else if(splitted_word.indexOf(splitted_input[i]) >= 0){
							
								column[i].getElementsByTagName("p")[0].style.backgroundColor = "#e2e23e";
								console.log(splitted_input[i] + " exsist");

							// Letter does not exsist
							} else {
								console.log(splitted_input[i] + " does not exsists");
							}

						}, i * 500, i);			
					}		
				}
			}

			return false;
		},

		run: function(){
			var submit 	= document.getElementById("playboard_submit");
			var row 	= 0;
			
			submit.addEventListener('click', function(){

				if(	lingo.validateUserInput() &&
					lingo.insertLettersInGrid(row++) != false
				){	
					// Check if user input match word.	
					lingo.checkWord(row);
				} 
			});
		},

		// Game over
		gameOver: function(){
			var el = document.getElementById("time");
			el.innerHTML = "Game over";
			// Stop game
			// Show game over on screen.
			// save score (see scoreboard)
		}
	}

	lingo.startGame();
});
