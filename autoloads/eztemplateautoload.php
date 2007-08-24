<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
  array( 'script' => 'extension/flickr/autoloads/flickr_tag_search.php',
         'class' => 'FlickrTagSearch',
         'operator_names' => array( 'flickr_tag_search' ) );

?>