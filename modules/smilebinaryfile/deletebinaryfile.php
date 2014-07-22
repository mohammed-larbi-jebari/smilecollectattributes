<?php
$http = eZHTTPTool::instance();
$module = $Params['Module'];

$viewMode='full';
if ( $module->hasActionParameter( 'LanguageCode' ) )
    $languageCode = $module->actionParameter( 'LanguageCode' );
else
{
    $languageCode = false;
}

if ( $http->hasPostVariable( "ActionDeleteSmileBinaryFile" ) AND
     $http->hasPostVariable( "AttributeVersion" )
   )
{
     $ActionDeleteSmileBinaryFile  = $http->postVariable( "ActionDeleteSmileBinaryFile" );
     $AttributeVersion             = $http->postVariable( "AttributeVersion" );
     $ContentObjectID              = $http->postVariable( "ContentObjectID" );
     $contentObjectAttributeIds    = array_keys($ActionDeleteSmileBinaryFile);
     
    foreach ($contentObjectAttributeIds as $contentObjectAttributeId)
    {
        if(isset($AttributeVersion[$contentObjectAttributeId]) )
        {
            $version                  = $AttributeVersion[$contentObjectAttributeId];
            $contentObjectAttribute   = eZContentObjectAttribute::fetch($contentObjectAttributeId, $version);

            if($contentObjectAttribute)
            {
              smileBinaryFileType::deleteObjectAttribute( $contentObjectAttribute, $version );
              eZContentCacheManager::clearContentCache( $ContentObjectID );
            }
        }
    }
}
if ( $http->hasPostVariable( "ContentNodeID" ) )
     {
       $node =  eZContentObjectTreeNode::fetch($http->postVariable( "ContentNodeID" ));

       if($node)
       {
       $module->redirectTo($node->urlAlias() );
       return;
       }
     }
     return;