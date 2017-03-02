<!-- Page Preloader -->
<div id="preloader">
    <div id="spinner"></div>
</div>
<!-- /Page Preloader -->

<!--Pop-up model-->
<section id="pop_up_model">
	<div id="model_content">

		<h3>Lingo</h3>

		<!-- Single Player section -->
		<button id="single_player_button">Single Player</button>

		<?=form_open('home', 
			[
				'class' => 'form',
				'id' => 'popup_form'
			]
		);?>
			<div id="single_player_content">
				<h6>Options</h6>
				<label for="option_letters">Aantal letters</label>
				<input type="number" name="set_letter_count" id="set_letter_count" placeholder="Standaard is 5">

				<input type="submit" name="submit_popup" class="play_game button outline" value="play">
			</div>
		</form> <!-- / popup_form -->
	</div>
</section>

<main class="container">
	<!-- Navigation -->
	<nav>
		<span id="open_nav">&#9776;</span>
		<section id="scoreboard">
			<span id="player_one_score" class="label base focus"></span>
			<span id="player_two_score" class="label base success">3000</span>
		</section>
	</nav>

	<!-- Playboard -->
	<section id="play_board">
		<?php if(in_array("guest", $scopes)) echo "I AM A GUEST!"; ?>
	</section>

	<!-- User options & input -->
	<section id="user_options"></section>
	<section id="user_input"></section>

	<!-- Sidebar -->
	<aside id="side_bar">
		<!-- Close button -->
		<a href="javascript:void(0)" id="close_nav">&times;</a>
		
		<section id="profile">
			<div id="avatar"></div>
			<p id="username">User: <?=$username?></p>
			<p id="room_name">Room: sjx23424</p>

			<a href="logout" id="logout">Logout</a>
		</section>
		<section id="spectators">
			
		</section>
	</aside>
</main>

<!-- SCRIPTS -->
<script id="del" src="<?php echo base_url('build/js/scripts.js') ?>"></script>