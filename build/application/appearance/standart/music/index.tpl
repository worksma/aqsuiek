<div class="row Music-Page-Main">
	<div class="col-lg-3 mobile-hide">
		{grab('/elements/navigation/profile-mini.tpl')}
		{grab('/elements/navigation/menu.tpl')}
	</div>
	
	<div class="col-lg-9 Music-Page-Main">
		<div class="block Music-Player">
			<div class="Control">
				<audio id="Player"></audio>
			
				<button class="Skip-Backward">
					<i class="bi bi-skip-backward-fill"></i>
				</button>
				
				<button class="Pause-And-Play">
					<i class="bi bi-play-circle-fill"></i>
				</button>
				
				<button class="Skip-Forward">
					<i class="bi bi-skip-forward-fill"></i>
				</button>
				
				<button class="Repeat">
					<i class="bi bi-repeat"></i>
				</button>
			</div>
			
			<div class="Info">
				<div class="Image">
					<img src="">
				</div>
				
				<div class="Data">
					<div class="Track-Name"></div>
					<div class="Artist"></div>
					
					<div class="RangeContainer">
						<div class="Fill"></div>
						<input type="range" min="0" max="100" value="0" id="PlayerPosition">
					</div>
				</div>
			</div>
			
			<div class="Control-End">
				<div class="Volume-Control">
					<button>
						<i class="bi bi-volume-up-fill"></i>
					</button>
					
					<div class="RangeContainer">
						<div class="Fill"></div>
						<input type="range" min="0" max="1" step="0.01" value="1" id="PlayerVolume">
					</div>
				</div>
			</div>
		</div>
		
		<div class="block Music-List">
			<form class="Music-Search">
				<input type="text" class="form-control" name="q" autocomplete="off" placeholder="Поиск музыки">
				
				<button type="submit">
					<i class="bi bi-search"></i>
				</button>
			</form>
			
			<ul id="Music-List">
				{func MusicPlayer:getList('{userid}')}
			</ul>
		</div>
		
		<script>
			$(function() {
				playList = {musicjson};
			});
		</script>
	</div>
</div>