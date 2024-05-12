<div class="Blog-Post-Page">
	<div class="Title">{post_name}</div>
	
	<div class="Author">
		<a href="/blog{blog_id}"><img src="/public/images/blog/{blog_image}"></a>
		
		<div class="Data">
			<div class="Name"><a href="/blog{blog_id}">{blog_name}</a></div>
			<div class="Date">{post_date}</div>
		</div>
	</div>
	
	<div class="Panel">
		<span>
			<i class="bi bi-heart"></i> {likes}
		</span>
		
		<span>
			<i class="bi bi-eye"></i> {views}
		</span>
		
		<span class="Share">
			<a href="https://vk.com/share.php?url=https://{{$_PAGE['url']}}"><i class="bi bi-share"></i></a>
		</span>
	</div>
	
	<div class="Content">
		<img src="/public/images/blog/{post_image}" data-target="Zoom" data-toggle="Image">
		{post_content}
	</div>
</div>