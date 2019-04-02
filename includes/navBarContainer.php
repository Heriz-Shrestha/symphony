<div id="navBarContainer">
	<nav class="navBar">

		<span role="link" tabindex= "0" onclick = "openPage('index.php')" class="logo">
			<img src="assets/images/icons/logo.jpg">
		</span>


		<div class="group">

			<div class="navItem">
				<span role="link" tabindex= "0" onclick="openPage('search.php')" class="navItemLink">Search
					<img src="assets/images/icons/search.png" class="icon" alt="Search">
				</span>
			</div>

		</div>

		<div class="group">
			<?php if($_SESSION['userLoggedIn']){ ?>
				<div class="navItem">
					<span role="link" tabindex= "0" onclick = "openPage('browse.php')" class="navItemLink">Browse</span>
				</div>
				
				<div class="navItem">
					<span role="link" tabindex= "0" onclick = "openPage('yourMusic.php')"" class="navItemLink">Your Music </span>
				</div>
				<div class="navItem">
					<span role="link" tabindex= "0" onclick = "openPage('profile.php')" class="navItemLink"><?php echo $userLoggedIn->getFirstAndLastName(); ?> </span>
				</div>
				<?php if($userLoggedIn->getCategory() == 0){ ?>
				
						<div class="navItem">
						<span role="link" tabindex= "0" onclick = "openPage('adminPage.php')"" class="navItemLink">Admin Pannel </span>
						</div>
				<?php	} ?>
			<?php } ?>
			<?php if(!$_SESSION['userLoggedIn']){ ?>		
				<div class="navItem">
					<a href="register.php"><button class="button"> LOGIN </button></a>
				</div>
			<?php } ?>
			
		</div>
	</nav>
</div>