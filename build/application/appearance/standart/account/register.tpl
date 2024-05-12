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
				
			<form data-target="Register">
				<div class="mb-2 d-flex gap-2">
					<div class="form-floating">
						<input id="floatingInputFirstname" type="text" class="form-control" placeholder="Имя" name="first_name" autocomplete="off" minlength="2" maxlength="32" required>
						<label for="floatingInputFirstname">Имя</label>
					</div>
					
					<div class="form-floating">
						<input id="floatingInputLastname" type="text" class="form-control" placeholder="Фамилия" name="last_name" autocomplete="off" minlength="2" maxlength="32" required>
						<label for="floatingInputLastname">Фамилия</label>
					</div>
				</div>
				
				<div class="mb-2">
					<div class="form-floating">
						<select id="floatingInputSex" class="form-select" name="sex" autocomplete="off" required>
							<option value="1">Мужской</option>
							<option value="2">Женский</option>
						</select>
					
						<label for="floatingInputSex">Гендер</label>
					</div>
				</div>
				
				<div class="mb-2">
					<div class="form-floating">
						<input type="date" class="form-control" placeholder="Дата рождения" id="floatingBirthday" name="birthday" value="{{date('Y-m-d')}}">
						<label for="floatingBirthday">Дата рождения</label>
					</div>
				</div>
				
				<div class="mb-2">
					<div class="form-floating">
						<input id="floatingInputNickname" pattern="[a-zA-Z0-9_]+" type="text" class="form-control" placeholder="Псевдоним" name="nickname" autocomplete="off" minlength="2" maxlength="32" onkeyup="removeSpacesAndDashes()" placeholder="Введите текст" required>
						<label for="floatingInputNickname">Псевдоним</label>
					</div>
				</div>
				
				<div class="mb-2">
					<div class="form-floating">
						<input id="floatingInputEmail" type="email" class="form-control" placeholder="E-mail адрес" name="email" autocomplete="off" required>
						<label for="floatingInputEmail">E-mail адрес</label>
					</div>
				</div>
				
				<div class="{if(conf()->beta == '1')}mb-2{else}mb-3{/if}">
					<div class="form-floating">
						<input id="floatingInputPassword" type="password" class="form-control" placeholder="Пароль" name="password" autocomplete="off" required>
						<i class="bi bi-eye-slash" id="togglePassword"></i>
						<label for="floatingInputPassword">Пароль</label>
					</div>
				</div>
				
				{if(conf()->beta == '1')}
					<div class="input-group mb-2">
						<input type="text" class="form-control" placeholder="Бета-ключ" name="beta" autocomplete="off" required>
					</div>
					
					<div class="alert info mb-3">
						Используйте полученный Бета ключ, чтобы получить статус Бета тестера
					</div>
				{/if}
				
				<div class="mb-3">
					<input type="submit" class="btn btn-lg btn-primary w-100" value="Продолжить" id="next">
				</div>
			</form>
			
			<div class="row">
				<div class="col text-center"><a href="/account/auth">У вас уже есть профиль?</a></div>
			</div>
		</div>
	</div>
	
	<div class="right">
		<div class="content">
			<img src="/public/assets/{appearance}/img/custom/register.svg">
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
	function removeSpacesAndDashes() {
		var inputField = document.getElementById("floatingInputNickname");
		var inputValue = inputField.value;
		var sanitizedValue = inputValue.replace(/[^\w]/g, '');

		inputField.value = sanitizedValue;
	}

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