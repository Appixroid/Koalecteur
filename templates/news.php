<div class="article" article id="<?php echo $_NEWS["id"];?>">
	<hr>
	<a target="_blank" href="<?php echo $_NEWS["link"];?>">
	<div class="title<?php echo rand(1, 3); ?>"><?php echo $_NEWS["title"];?></div>
	<div class="date"><?php echo $_NEWS["date"];?></div>
	<hr>
	<div class="description"><?php echo $_NEWS["desc"];?></div>
	<?php if($_NEWS["enclosure"] != null && explode("/", $_NEWS['enclosure'][0])[0] == "image"){
		?>
			<figure class="figure">
				<img class="media" src="<?php echo $_NEWS['enclosure'][1]?>" alt="">
			</figure>
		<?php
	} ?>
	</a>
	<hr>
</div>
