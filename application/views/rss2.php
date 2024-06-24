<?php
/* @var $channel Channelobj */
header("Content-type: text/xml;charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<rss version="2.0"
	 xmlns:content="http://purl.org/rss/1.0/modules/content/"
	 xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	 xmlns:dc="http://purl.org/dc/elements/1.1/"
	 xmlns:atom="http://www.w3.org/2005/Atom"
	 xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	 xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	 >
	<channel>
		<title><?= $channel->title ?></title>
		<atom:link href="<?= base_url() ?>" rel="self" type="application/rss+xml" />
		<link><?= $channel->link ?></link>
		<description><?= $channel->description ?></description>
		<generator><?= $channel->generator ?></generator>
		<webMaster><?= $channel->web_master ?></webMaster>
		<language>ja</language>
		<?php foreach ($channel->items as $item) { ?>
			<item>
				<title><?= $item->title ?></title>
				<link><?= $item->link ?></link>
				<description><?= $item->description ?></description>
				<author><?= $item->author ?></author>
				<category><?= $item->category ?></category>
				<pubDate><?= $item->get_pub_date() ?></pubDate>
				<guid isPermaLink="false"><?= $item->guid ?></guid>
			</item>
		<?php } ?>
	</channel>
</rss>