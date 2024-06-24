<?php

/**
 * need include func_helper.php
 */

if (!function_exists('tag_script_js'))
{
	function tag_script_js($src, $charset = 'UTF-8')
	{
		// TODO: check full url
		// TODO: check exists extension
		return '<script src="' . $src . '" type="text/javascript" charset="' . $charset . '"></script>';
	}
}


if (!function_exists('tag_icon'))
{

	function tag_icon($class_suffix, $class_prefix = 'glyphicon')
	{
		return '<i class="' . $class_prefix . ' ' . $class_prefix . '-' . $class_suffix . '"></i>';
	}

}

if (!function_exists('tag_icon_fa'))
{

	function tag_icon_fa($class_suffix)
	{
		return tag_icon($class_suffix, 'fa');
	}

}

if (!function_exists('attr_href'))
{

	function attr_href($type = PATH_TOP, $values = NULL, $is_wrap_base = FALSE)
	{
		$link = $type;
		// TODO: support array $option_value args
		if (!empty($values))
		{
			if (!is_array($values))
			{
				$values = explode('/', $values);
			}
			$link .= '/' . implode('/', urlencode_array($values));
		}

		if ($is_wrap_base)
		{
			$link = base_url($link);
		}
		return 'href="' . $link . '"';
	}

}

if (!function_exists('attr_tooltip'))
{

	function attr_tooltip($str)
	{
		return ' data-toggle="tooltip" data-placement="top" title="' . $str . '"';
	}

}

if (!function_exists('harebtn_twitter'))
{

	function sharebtn_twitter($text, $uri, $name_text = 'ツイートする', $is_count = TRUE, $is_large = FALSE)
	{
		?>
		<a href="http://twitter.com/share" class="twitter-share-button"
			 data-url="<?= fix_url($uri) ?>"
			 data-text="<?= $text ?>"
			 <?= $is_large ? 'data-size="large"' : '' ?>
			 data-count=<?= $is_count ? "horizontal" : "none" ?>
			 data-lang="ja"><?= $name_text ?></a>
		<?php
	}

}
