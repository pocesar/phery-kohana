<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Phery_JS extends Controller {

	function action_index()
	{
        $lastModified = filemtime(PHERY_JS);
        //get a unique hash of this file (etag)
        $etagFile = md5_file(PHERY_JS);
        //get the HTTP_IF_MODIFIED_SINCE header if set
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        $this->response->headers(array(
            //set last-modified header
            'Last-Modified' => gmdate("D, d M Y H:i:s", $lastModified)." GMT",
            //set etag-header
            'ETag' => "\"$etagFile\"",
            //make sure caching is turned on
            'Cache-Control' => 'public',
            'Content-Type' => 'application/javascript;charset=utf-8'
        ));

        //check if page has changed. If not, send 304 and exit
        if (($ifModifiedSince && @strtotime($ifModifiedSince) == $lastModified) || ($etagHeader && $etagHeader == $etagFile))
        {
               $this->response->status(304);
        }
        else
        {
            $javascript = file_get_contents(PHERY_JS);

            $this
                ->response
                ->body($javascript);
        }
	}
}
