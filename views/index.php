<h1>Welcome to microPHP</h1>
<ul>
	<?php foreach ($this->data->posts as $post): ?>
		<li>
			<a href="/post/<?php echo $post->id ?>">
				<?php echo $post->title ?>
			</a>
		</li>
	<?php endforeach ?>
</ul>