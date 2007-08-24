<?php

class FlickrTagSearch
{
    /*!
     Constructor
    */
    function FlickrTagSearch()
    {
        $this->Operators = array( 'flickr_tag_search');
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list
    exists per operator type, this is needed for operator classes
    that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     The first operator has two parameters, the other has none.
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array(                      
                      'flickr_tag_search' => array('api_key' => array( 'type' => 'string',
                                                                     'required' => true,
                                                                     'default' => '' ),
                                                'tag' => array( 'type' => 'string',
                                                                     'required' => true,
                                                                     'default' => '' ),
                                                'per_page' => array( 'type' => 'string',
                                                                     'required' => true,
                                                                     'default' => '' )
                                            ) );
    }

    /*!
     Executes the needed operator(s).
     Checks operator names, and calls the appropriate functions.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace,
                     &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'flickr_tag_search':
            {
                $operatorValue = $this->flickr_tag_search( $namedParameters['api_key'], 
                                                        $namedParameters['tag'], 
                                                        $namedParameters['per_page']);
            } break;
        }
    }

    function flickr_tag_search( $api_key, $tag, $per_page  )
    { 

        $params = array(
	'api_key'	=> $api_key,
	'method'	=> 'flickr.photos.search',
	'tags'		=> $tag,
        'per_page'	=> $per_page,
	'format'	=> 'php_serial',
        );

        $encoded_params = array();

        foreach ($params as $k => $v){
            $encoded_params[] = urlencode($k).'='.urlencode($v);
        }

        $url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

        $rsp = file_get_contents($url);

        $rsp_obj = unserialize($rsp);

        if ($rsp_obj['stat'] == 'ok'){

            $result = '<ul>';

            for ($i=0; $i<$per_page; $i++) {
                $myphoto = $rsp_obj['photos']['photo'][$i];

                $photo_url = 'http://static.flickr.com/';
                $photo_url .= $myphoto['server'] . '/' . $myphoto['id'] . '_' .                 $myphoto['secret'];
                $photo_url .= '_t';
                $photo_url .= '.jpg';

                $flickr_url = 'http://www.flickr.com/photos/';
                $flickr_url .= $myphoto['owner'] . '/' . $myphoto['id'];

                $result .= '<li><a href="' . $flickr_url . '"><img src="' . $photo_url . '" alt="' . $myphoto['title'] . '" title="' . $myphoto['title'] . '" /></a></li>';
            }
            $result .= '</ul>';
        } else {
            $result = 'Unable to load flickr images.';
        }
    return $result;
}

    /// \privatesection
    var $Operators;
}

?>