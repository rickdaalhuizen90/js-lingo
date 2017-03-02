// Game functionalities
window.addEventListener('load', function(){

	var token = {

		// JWT
		parseJwt: function(token){
			var base64Url 	= token.split('.')[1];
        	var base64 		= base64Url.replace('-', '+').replace('_', '/');
        	return JSON.parse(window.atob(base64));
		}
	}

	var sidebar = {
		// Sidebar
		openNav: function() {
		    document.getElementById("side_bar").style.width = "250px";
		    document.getElementById("open_nav").addEventListener("click", openNav);
		}

		closeNav: function() {
		    document.getElementById("side_bar").style.width = "0";
		    document.getElementById("close_nav").addEventListener("click", closeNav);
		}
	}

	var userProfile = {

		 // Profile
	    player: 	parseJwt(document.cookie);
	    playerName: this.player.name;
	    avatar:  	this.player.img;
	    email: 		this.player.email;

	    // Show user profile in sidebar
	    renderToPage: function(){
	    	document.getElementById('avatar').style.backgroundImage = "url(" + this.avatar + ")";
			document.getElementById('username').innerHTML = "Player: " + this.playerName;
			document.getElementById('room_name').innerHTML = "Room: XCV4837764";
	    }
	}

	var scoreboard = {

		// Show player scores on page
		renderToPage: function(){
			document.getElementById("player_one_score").innerHTML = playerName + ": 2500";
			document.getElementById("player_two_score").innerHTML = "Kevin: 3000";
		}
	}

	var popUpModel = {
	    
	    // Buttons    
	    play_game_button: document.getElementsByClassName("play_game");
	 
	    // Model Content 
	    mp_content: document.getElementById("multi_player_content");
	}

	var gameConfigurations = {
		
	
	}

	var roomOptions = {

		// Generate a random string(room_name)
	    genRandomString: function(){
		    var text = "";
		    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		    for( var i=0; i <= 6; i++ )
		        text += possible.charAt(Math.floor(Math.random() * possible.length));

		    return text;
	    }
	}

	var singlePlayer = {

		sp_button: document.getElementById("single_player_button");

	}

	var multiPlayer = {

	}
});