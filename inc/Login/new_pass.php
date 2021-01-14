<div class="wrapper fadeInDown">
    <div id="formContent">
          <!-- Tabs Titles -->

          <!-- Icon -->
        <div class="fadeIn first">
        	 <h1 style="margin: 15px;">Cambiar Contraseña</h1>
        </div>

          <!-- Login Form -->
			<form class="login-form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input type="password" name="act_pass" class="fadeIn second" placeholder="Contraseña Actual" required>
					<input  minlength="8" type="password" name="new_pass" class="fadeIn third" placeholder="Nueva Contraseña" required>
					<input minlength="8" type="password" name="new_pass_confirm" class="fadeIn third" placeholder="Confirmar Nueva Contraseña" required>
          <input type="submit" name="new_password" value="Confirmar" class="fadeIn fourth">
          <input type="button" style="background-color: #DC3545" name="cancelar_new_password" value="Cancelar" class="fadeIn five" onclick="location.href='index.php?ACTION=profesores'">
			</form>
    </div>
</div>