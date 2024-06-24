<?php ?>
<div class="content">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">概要</a></li>
		<li><a href="#qa" data-toggle="tab">Q&amp;A</a></li>
		<li><a href="#link" data-toggle="tab">相互リンク</a></li>
	</ul>
	<div id="help-contents" class="tab-content">
		<div class="tab-pane fade active in" id="home">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<?php $this->load->view('helppagehome'); ?>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="qa">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<?php $this->load->view('helppageqa'); ?>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="link">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<?php $this->load->view('helppagelink'); ?>
				</div>
			</div>
		</div>
	</div>
</div>