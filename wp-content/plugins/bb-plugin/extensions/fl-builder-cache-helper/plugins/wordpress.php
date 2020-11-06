<?php
namespace FLCacheClear;
class Wordpress {
	var $name    = 'Object Caching';

	function run() {
		wp_cache_flush();
	}
}
