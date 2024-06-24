<?php
/* @var $games_hot Gameobj[] */
/* @var $games_new Gameobj[] */
/* @var $games_recent Gameobj[] */
/* @var $tags string[] */
/* @var $category string[] */
?>

<div class="content">
	<div class="row">
		<div class="col-md-offset-7 col-md-5">
			<?php
			$this->load->view('searchform');
			?>
		</div>
	</div>
	<?php
	$this->load->view('categorynav', array('category' => $category));
	$this->load->view('topmain', array('games_hot' => $games_hot, 'games_new' => $games_new, 'games_recent' => $games_recent, 'tags' => $tags, 'category' => $category));
	?>
</div>