<!-- Page Preloader -->
<!-- <div id="preloader">
    <div id="spinner"></div>
</div> -->
<!-- /Page Preloader -->

<!-- Countdown -->
<div id="countdown"></div>
<!-- /Countdown -->

<!--Pop-up model-->
<section id="pop_up_model">
	<div id="model_content">

		<div id="logo"></div>

		<!-- Single Player section -->
		<button id="single_player_button">Single Player</button>

		<?=form_open('home', 
			[
				'class' => 'form',
				'id' => 'popup_form'
			]
		);?>
			<div id="single_player_content">
				<h6>Game mode</h6>
				<select name="set_game_mode" class="button secondary set_game_mode">
				  	<option value="4x4">4 X 4</option>
				  	<option value="5x5">5 X 5</option>
				  	<option value="6x6">6 X 6</option>
				</select>

				<input type="submit" name="submit_popup" class="play_game button" value="play">
			</div>
		</form> <!-- / popup_form -->
	</div>
</section>

<main class="container">
	<!-- Scoreboard -->
	<section id="scoreboard" class="col row auto">
		<div id="score" class="col col-4"></div>
		<div id="time" class="col col-4"></div>
		<div id="lives" class="col col-4"></div>
	</section>

	<!-- Playboard -->
	<section id="playboard_grid"></section>

	<span id="playboard_user_input">
		<input id="playboard_form" type="text" min="0">
		<button id="playboard_submit">Submit</button>
	</span>

	<!-- User options & input -->
	<section id="user_options"></section>
	<section id="user_input"></section>

	<!-- Error message -->
	<p id="error_message"></p>

	<!-- Menu button -->
	<div id="toggle_nav">menu</div>
</main>


<!-- Menu -->
<div id="menu">
	<section id="profile">
		<div id="avatar"></div>
		<p id="username">User: <?=$username?></p>
		<p id="room_name">Room: sjx23424</p>

		<a href="logout" id="logout">Logout</a>
	</section>
</div>

<!-- SCRIPTS -->
<script id="del" src="<?php echo base_url('build/js/scripts.js') ?>"></script>