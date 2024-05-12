<div class="dropdown noty">
	<div class="dropdown-toggle d-flex justify-content-center align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
		<span class="bell">
			<i class="bi bi-bell"></i>
			
			{if('{bells}' != '0')}
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
					<div id="NotyCount">{bells}</div>
					<span class="visually-hidden">unread messages</span>
				</span>
			{/if}
		</span>
		
		<ul class="dropdown-menu dropdown-menu-end">
			{list}
		</ul>
	</div>
</div>