'use strict';

window.addEventListener('load', function() {
    
    // Get JWT
    var token = {
        // JWT
        parseJwt: function(token) {
            var base64Url = token.split('.')[1];
            var base64 = base64Url.replace('-', '+').replace('_', '/');
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

            for (var i = 0; i < ca.length; i++) {
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
        },

        updateCookie: function setCookie(cname, cvalue) {
            document.cookie = cname + "=" + cvalue;
        }

    }
    var logout = {

        //Empty local storage
        purgeLocalStorage: function() {
            var logout_btn = document.getElementById("logout");
            logout_btn.addEventListener('click', function() {
                localStorage.removeItem("done");
            });
        },

        action: function() {
            this.purgeLocalStorage();
        }
    }
    logout.action();

    // Sidebar object
    var sidebar = {
     
        // Toggle sidebar
        toggle: function() {
            var sideBar = document.getElementById("side_bar");
            var toggle = document.getElementById("toggle_nav");
     
            toggle.addEventListener("click", function() {
                if (document.body.style.marginTop === "-500px") {
                    document.body.style.marginTop = "0px";
                } else {
                    document.body.style.marginTop = "-500px";
                }
            })
        },
     
        // User profile
        profile: function() {
            var obj = token.parseJwt(document.cookie);
            return {
                id: obj.id,
                name: obj.name,
                avatar: obj.img,
                email: obj.email,
                roles: obj.scopes,
                expire: obj.exp
            };
        },
     
        // Show user profile in sidebar
        render: function() {
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
        toggle: function() {
    
            // Buttons
            var sp_btn = document.getElementById("single_player_button");
    
            // Content
            var sp = document.getElementById("single_player_content");
            sp.style.display = "none";
    
            // Button event for sinle player btn
            sp_btn.addEventListener('click', function() {
                if (sp.style.display == "none") {
                    sp.style.display = "block";
                } else {
                    sp.style.display = "none";
                }
            });
        },
    
        render: function() {
            var model_content = this.content;
            var play = this.play_btn;
    
            if (localStorage["done"]) {
                model_content.style.display = "none";
            } else {
                model_content.style.display = "block";
            }
    
            play[0].addEventListener('click', function() {
                localStorage['done'] = true;
            });
        },
    }
    popUpModel.toggle();
    popUpModel.render();
    
    // Show player scores
    var scoreboard = {
        gameObject: cookies.getCookie("game_current"),
        scores: [],

        // Show player lives on page
        lives: function() {
            var lives = document.getElementById("lives");
            var obj = this.gameObject;
            lives.innerHTML = "Lives: " + obj.lives;
        },
    
        // Show timer on page
        time: function(minutes, seconds) {
            var el = document.getElementById("time");
            el.innerHTML = "Time: 0" + minutes + ":00";
    
            var time = minutes * 60 + seconds;
            var interval = setInterval(function() {
    
                if (time <= 0) {
                    clearInterval(interval);
                    lingo.gameOver(el);
                }
    
                var minutes = Math.floor(time / 60);
    	            if (minutes < 10) minutes = "0" + minutes;
                var seconds = time % 60;
        	        if (seconds < 10) seconds = "0" + seconds;
            
                el.innerHTML = "Time: " + minutes + ':' + seconds;
                time--;
            }, 1000);
        },

        countScore: function() {
            // Random word & input value
            var object = this.gameObject.word;
            var word = lingo.validateUserInput();
            
            // Splitted random word & input value
            var word = word.split("");
            var guess_word = object.split("");

            for(var i = 0; i < word.length; i++){

                if(guess_word[i] == word[i]) {

                    this.scores.push(20);
                    guess_word[i] = ""; 
                }else if(guess_word.includes(word[i])){  
                    this.scores.push(10);
                    word[i] = ""; 
                }
            }

            // Get the sum of the numbers in the array:
            var total_score = this.scores.reduce(function(acc, val){ 
                return acc + val; 
            }, 0);

            
            this.showScore(total_score);
        },
        
        showScore: function(total_score = 0) {
            var score_el = document.getElementById("score");
            score_el.innerHTML = "Score: " + total_score;
        },
        
        render: function() {
            this.showScore();
            this.time(3, 0);
            this.lives();
        },
    }
    scoreboard.render();

    var lingo = {
        gameObject: cookies.getCookie("game_current"),
        gameConfig: cookies.getCookie("game_config"),
    
        drawGrid: function() {
            var grid = document.getElementById("playboard_grid");
            var row_count = this.gameConfig.game_mode;
            var column_count = this.gameConfig.game_mode;
    
            // Create rows
            for (var a = 1; a <= row_count; a++) {
                var row = document.createElement("div");
                row.className = "playboard_row row_" + a;
                grid.appendChild(row);
     
                // Create columns
                for (var b = 0; b < column_count; b++) {
                    var column = document.createElement("div");
                    column.className = "col col-4 playboard_column";
                    document.getElementsByClassName("row_" + a)[0].appendChild(column);
                }
            }
        },

        playboardForm: function() {
            var form = document.getElementById("playboard_form");
            var max = this.gameConfig.game_mode;
            form.max = parseInt(max);
        },
    
        validateUserInput: function() {
            var input_value = document.getElementById("playboard_form").value;
            var str_length = this.gameConfig.game_mode;
            var regex = /^[a-zA-Z]+$/;
            var errorMsg = document.getElementById("error_message");
    
            /**
            * Check if input value is not empty
            * Check if input value match the proper word length
            * Check input value for special symbols
            */
            if (input_value != "" && 
                input_value.length === str_length &&
                input_value.match(regex)
            ) {
                return input_value.toUpperCase();
            } else {
    
                errorMsg.style.display = "block";
                if (input_value.length !== str_length) {
                    var msg = "Word should be " + str_length + " letters long!";
                } else {
                    var msg = "Oops input value is not valid";
                }
    
                // Return error message.
                errorMsg.innerHTML = msg;
                setInterval(function() {
                    errorMsg.style.display = "none";
                    errorMsg.innerHTML = "";
                }, 5000);
                return false;
            }
        },
    
        insertLettersInGrid: function(row = 0) {
            var letters = this.validateUserInput();
            var letters = letters.split("");
    
            if (row < letters.length) {
                var rows = document.getElementsByClassName("playboard_row")[row];
                var column = rows.getElementsByClassName("playboard_column");
    
                // Insert letters in the right row and columns.
                for (var i = 0; i < letters.length; i++) 
                    column[i].innerHTML += "<p>" + letters[i] + "</p>";
                
                return true;
            } else {
                console.log("Game over!");
                document.getElementById("time").innerHTML = "Game over!";
            }

            return false;
        },

        checkWord: function(row = 0) {
            // Splitted random word & input value
            var word = this.validateUserInput().split("");
            var guess_word = this.gameObject.word.split("");

            // Grid elements
            var rows   = document.getElementsByClassName("playboard_row")[row - 1];
            var column = rows.getElementsByClassName("playboard_column");

            for(var i = 0; i < word.length; i++){

                var doCheck = function(i) {

                    var temp = column[i].getElementsByTagName("p")[0];
                    console.log(temp);

                    if(guess_word[i] == word[i]) {
                        temp.className += " correct_position";
                        guess_word[i] = "";    
                    }else if(guess_word.includes(word[i])){    
                        temp.className += " correct_letter";
                        word[i] = "";   
                    }
                }

                setTimeout(doCheck, i * 500, i); 
            }
        },

        gameWon: function() {
            var word = this.validateUserInput();
            var guess_word = this.gameObject.word;
            var lives = cookies.getCookie("game_current");

            if (word == guess_word) {
                alert("You won!");
                // Get new random word.
                // Save score in leaderboard (Ajax call)
            } else if (word != guess_word) {
                var column = document.querySelector(".playboard_row.row_" + guess_word.length + " .col");

                if (column.childNodes.length != 0) {
                    alert("You lose!");
                    // Remove 1 life.
                }
            }
        },

        gameLose: function() {

        },

        formSubmit: function() {
            var submit = document.getElementById("playboard_submit");
            var row = 0;
            var timeout = cookies.getCookie("game_current").word.length;
            
            submit.addEventListener('click', function() {

                if (lingo.validateUserInput() && lingo.insertLettersInGrid(row++) != false) {
                    // Check if user input match word.	
                    lingo.checkWord(row);

                    // Put functions  end of the stack.
                    setTimeout(function(){
                        scoreboard.countScore();
                        lingo.gameWon();
                    }, timeout * 500);
                }
            });
        },

        startGame: function() {
            if (localStorage['done'] = true) {
    
                // Init random word
                var randomWord = this.gameObject.word;
                console.log("Random word: " + randomWord);
    
                this.drawGrid();
                this.playboardForm();
                this.formSubmit();
            };
        },

        // Game over
        gameOver: function() {
            var el = document.getElementById("time");
            el.innerHTML = "Game over";
            // Stop game
            // Show game over on screen.
            // save score (see scoreboard)
        }
    }
    lingo.startGame();
});