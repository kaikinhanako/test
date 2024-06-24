<?php
/* @var $games_hot Gameobj[] */
/* @var $games_new Gameobj[] */
/* @var $games_recent Gameobj[] */
/* @var $tags string[] */
?>

<div class="content">
	<?php $this->load->view('jumbotron'); ?>
	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<?php
			$this->load->view('searchform');
			?>
		</div>
	</div>
	<?php $this->load->view('categorynav', array('category' => GAME_CATEGORY_ALL)); ?>
	<?php $this->load->view('topmain', array('games_hot' => $games_hot, 'games_new' => $games_new, 'games_recent' => $games_recent, 'tags' => $tags)); ?>
	<div class="row">
		<div class="col-md-6">
		</div>
		<div class="col-md-6">
			<a class="twitter-timeline"  href="https://twitter.com/search?q=ierukana%2Felzup.com"  data-widget-id="504209063970750464">ierukana/elzup.com に関するツイート</a>
		</div>
	</div>
</div>