<div class="wrapper fadeInDown">
    <div id="formContent">
          <!-- Tabs Titles -->

          <!-- Icon -->
        <div class="fadeIn first">
        	 <h1>Cambiar Contraseña</h1>
        </div>

          <!-- Login Form -->
			<form class="login-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input type="password" name="act_pass" class="fadeIn second" placeholder="Contraseña Actual" required>
					<input  minlength="8" type="password" name="new_pass" class="fadeIn third" placeholder="Nueva Contraseña" required>
					<input minlength="8" type="password" name="new_pass_c" class="fadeIn third" placeholder="Confirmar Nueva Contraseña" required>
					<input type="submit" name="new_password" value="Confirmar" class="fadeIn fourth">
			</form>
    </div>
</div>