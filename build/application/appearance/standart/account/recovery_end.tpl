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
				
			<form data-target="RecoveryEnd">
				<input type="hidden" name="hash" value="{hash}">
				
				<div class="mb-3">
					<div class="form-floating">
						<input id="floatingInputNewPassword" type="password" class="form-control" placeholder="Новый пароль" name="password" autocomplete="off" required>
						<i class="bi bi-eye-slash" id="togglePassword"></i>
						<label for="floatingInputNewPassword">Новый пароль</label>
					</div>
				</div>
				
				<div class="mb-3">
					<input type="submit" class="btn btn-lg btn-primary w-100" value="Сменить пароль">
				</div>
			</form>
			
			<div class="row">
				<div class="col text-center"><a href="/account/auth">Вспомнили пароль?</a></div>
			</div>
		</div>
	</div>
	<div class="right">
		<div class="content">
			<img src="/public/assets/{appearance}/img/custom/recovery.svg">
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
	
	#floatingInputNewPassword {
		padding-right: 40px;
	}
</style>

<script>
	$('#togglePassword').bind('click', function() {
		switch($('#floatingInputNewPassword').attr('type')) {
			case 'password': {
				$('#floatingInputNewPassword').attr('type', 'text');
				

				$(this).removeClass('bi-eye-slash');
				$(this).addClass('bi-eye');
				break;
			}
			
			default: {
				$('#floatingInputNewPassword').attr('type', 'password');
				
				$(this).removeClass('bi-eye');
				$(this).addClass('bi-eye-slash');
			}
		}
	});
</script>