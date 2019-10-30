<?php
namespace Sleek\CustomLogo;

add_filter('get_custom_logo', function ($html, $blogId) {
	$append = (is_array($blogId) and isset($blogId['append']) and !empty($blogId['append'])) ? $blogId['append'] : '';
	$inlineSvg = (is_array($blogId) and isset($blogId['svg']) and $blogId['svg']) ? true : false;

	# User has not defined a custom logo - include our own
	if (empty($html)) {
		# Path to logo without extension
		$path = get_stylesheet_directory() . "/dist/assets/site-logo$append";
		$uri = get_stylesheet_directory_uri() . "/dist/assets/site-logo$append";

		# Create meaningful alternative text
		$alt = get_bloginfo('name');

		if (get_bloginfo('description')) {
			$alt .= ' - ' . get_bloginfo('description');
		}

		# Check site-logo.svg
		if (file_exists("$path.svg")) {
			if ($inlineSvg) {
				$logo = file_get_contents("$path.svg");
			}
			else {
				$logo = "<img src=\"$uri.svg\" alt=\"$alt\">";
			}
		}
		# Check site-logo.png
		elseif (file_exists("$path.png")) {
			$logo = "<img src=\"$uri.png\" alt=\"$alt\">";
		}
		# Default to text
		else {
			$logo = get_bloginfo('name');
		}

		return '<a href="' . home_url('/') . '" class="site-logo">' . $logo . '</a>';
	}
	# User _has_ defined a custom logo - just replace the class name
	else {
		$html = str_replace('custom-logo-link', 'site-logo', $html);
	}

	return $html;
}, 10, 2);
