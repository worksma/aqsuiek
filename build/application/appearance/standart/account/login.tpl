<div class="panel">
	<div class="left">
		<div class="content">
			<div class="brand">
				<a href="/"><img src="/public/assets/{appearance}/img/logo.png"></a>
				<hr class="vertical">
				<div class="text">
					<p>AQSUIEK</p>
				</div>
			</div>
				
			<form data-target="Authorization">
				<div class="mb-2">
					<div class="form-floating">
						<input id="floatingInputEmail" type="email" class="form-control" placeholder="E-mail адрес" name="email" autocomplete="off" required>
						<label for="floatingInputEmail">E-mail адрес</label>
					</div>
				</div>
				
				<div class="mb-3">
					<div class="form-floating">
						<input id="floatingInputPassword" type="password" class="form-control" placeholder="Пароль" name="password" autocomplete="off" required>
						<i class="bi bi-eye-slash" id="togglePassword"></i>
						<label for="floatingInputPassword">Пароль</label>
					</div>
				</div>
				
				<div class="mb-3">
					<input type="submit" class="btn btn-lg btn-primary w-100" value="Войти">
				</div>
			</form>
						
			<div class="row">
				<div class="col recovery"><a href="/account/recovery">Забыли пароль?</a></div>
				<div class="col register"><a href="/account/register">Еще не с нами?</a></div>
			</div>
		</div>
	</div>
	<div class="right">
		<div class="content">
			<img src="/public/assets/{appearance}/img/custom/login.svg">
		</div>
	</div>
</div>

<style>
	form i {
		position: absolute;
		right: 14px;
		top: 20px;
		font-size: 18px;
		cursor: pointer;
	}
	
	#floatingInputPassword {
		padding-right: 40px;
	}
</style>

<script>
	$('#togglePassword').bind('click', function() {
		switch($('#floatingInputPassword').attr('type')) {
			case 'password': {
				$('#floatingInputPassword').attr('type', 'text');
				

				$(this).removeClass('bi-eye-slash');
				$(this).addClass('bi-eye');
				break;
			}
			
			default: {
				$('#floatingInputPassword').attr('type', 'password');
				
				$(this).removeClass('bi-eye');
				$(this).addClass('bi-eye-slash');
			}
		}
	});
</script>