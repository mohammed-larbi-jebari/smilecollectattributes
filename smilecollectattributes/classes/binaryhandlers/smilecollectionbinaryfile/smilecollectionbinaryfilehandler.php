<?php
/**
 * 
 * @author mojeb
 *
 */
class smileCollectionBinaryFileHandler
{
    const HANDLE_UPLOAD = 0x1;
    const HANDLE_DOWNLOAD = 0x2;
    
    const HANDLE_ALL = 0x3; // HANDLE_UPLOAD | HANDLE_DOWNLOAD
    
    const TYPE_FILE = 'file';
    const TYPE_MEDIA = 'media';
    
    const RESULT_OK = 1;
    const RESULT_UNAVAILABLE = 2;
    
    function smileCollectionBinaryFileHandler( $identifier, $name, $handleType )
    {
        $this->Info = array();
        $this->Info['identifier'] = $identifier;
        $this->Info['name'] = $name;
        $this->Info['handle-type'] = $handleType;
    }
    
    function attributes()
    {
        return array_keys( $this->Info );
    }
    
    function hasAttribute( $attribute )
    {
        return isset( $this->Info[$attribute] );
    }
    
    function attribute( $attribute )
    {
        if ( isset( $this->Info[$attribute] ) )
        {
            return $this->Info[$attribute];
        }
    
        eZDebug::writeError( "Attribute '$attribute' does not exist", __METHOD__ );
        return null;
    }
    
    /*!
     \return the suffix for the template name which will be used for attribute viewing.
    \note Default returns false which means no special template.
    */
    function viewTemplate( $contentobjectAttribute )
    {
        $retVal = false;
        return $retVal;
    }
    
    /*!
     \return the suffix for the template name which will be used for attribute viewing.
    \note Default returns false which means no special template.
    */
    function editTemplate( $contentobjectAttribute )
    {
        $retVal = false;
        return $retVal;
    }
    
    /*!
     \return the suffix for the template name which will be used for attribute viewing.
    \note Default returns false which means no special template.
    */
    function informationTemplate( $contentobjectAttribute )
    {
        $retVal = false;
        return $retVal;
    }
    /*!
     Figures out the filename from the binary object \a $binary.
     Currently supports eZBinaryFile, eZMedia and eZImageAliasHandler.
     \return \c false if no file was found.
     \param $returnMimeData If this is set to \c true then it will return a mime structure, otherwise it returns the filename.
     \deprecated
    */
    function storedFilename( &$binary, $returnMimeData = false )
    {

        $origDir = eZSys::storageDirectory() . '/original';

        $class = get_class( $binary );
        $fileName = false;
        $originalFilename = false;
        
        if ( $class == 'smileCollectionbinaryfile')
        {
            $fileName = $origDir . "/" . $binary->attribute( 'mime_type_category' ) . '/'.  $binary->attribute( "filename" );
            $originalFilename = $binary->attribute( 'original_filename' );
        }

        if ( $fileName )
        {
            $mimeData = eZMimeType::findByFileContents( $fileName );
            $mimeData['original_filename'] = $originalFilename;

            if ( !isSet( $mimeData['name'] ) )
                $mimeData['name'] = 'application/octet-stream';

            if ( $returnMimeData )
                return $mimeData;
            else
                return $mimeData['url'];
        }
        return false;
    }


    /*!
     \return the file object which corresponds to \a $contentObject and \a $contentObjectAttribute.
    */
    function downloadFileObject( $informationcollection, $contentObjectAttribute )
    {
        $$informationcollectionID = $informationcollection->attribute( 'id' );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        return smileCollectionBinaryFile::fetch( $informationcollectionID, $contentObjectAttributeID);
    }

    /*!
     \return the file object type which corresponds to \a $contentObject and \a $contentObjectAttribute.
     \deprecated
    */
    function downloadType( $informationcollection, $contentObjectAttribute )
    {
        return self::TYPE_FILE;
    }

    /*!
     \return the download url for the file object which corresponds to \a $contentObject and \a $contentObjectAttribute.
     \deprecated
    */
    function downloadURL( $informationcollection, $contentObjectAttribute )
    {

        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $informationcollectionID  = $informationcollection->attribute( 'id' );
        
        $downloadType   = self::downloadType( $informationcollection, $contentObjectAttribute );
        $downloadObject = self::downloadFileObject( $informationcollection, $contentObjectAttribute );
        
        $name = '';
        switch ( $downloadType )
        {
            case self::TYPE_FILE:
            {
                $name = $downloadObject->attribute( 'original_filename' );
            } break;
            case self::TYPE_MEDIA:
            {
                $name = $downloadObject->attribute( 'original_filename' );
            } break;
            default:
            {
                eZDebug::writeWarning( "Unknown binary file type '$downloadType'", __METHOD__ );
            } break;
        }
        $url = "/smilebinaryfile/download/$informationcollectionID/$contentObjectAttributeID/$downloadType/$name";
        return $url;
    }

    function handleDownload( $informationcollection, $contentObjectAttribute, $type )
    {

        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $contentObject            = $contentObjectAttribute->attribute( 'object' );
        $version = $contentObject->attribute( 'current_version' );



        if ( !$contentObjectAttribute->hasStoredFileInformation( $informationcollection, $version,
                                                                 $contentObjectAttribute->attribute( 'language_code' ) ) )
            return self::RESULT_UNAVAILABLE;

        $fileInfo = $contentObjectAttribute->storedFileInformation( $informationcollection, $version,
                                                                    $contentObjectAttribute->attribute( 'language_code' ) );

        if ( !$fileInfo )
            return self::RESULT_UNAVAILABLE;
        if ( !$fileInfo['mime_type'] )
            return self::RESULT_UNAVAILABLE;

        $contentObjectAttribute->handleDownload( $informationcollection, $version,
                                                 $contentObjectAttribute->attribute( 'language_code' ) );

       return $this->handleFileDownload( $informationcollection, $contentObjectAttribute, $type, $fileInfo );
    }

    function handleFileDownload( $contentObject, $contentObjectAttribute, $type, $mimeData )
    {
        return false;
    }

    function repositories()
    {
        return array( 'extension/smilecollectattributes/classes/binaryhandlers' );
    }

    /**
     * Returns a shared instance of the eZBinaryFileHandler class
     * pr $handlerName as defined in file.ini[BinaryFileSettings]Handler
     *
     * @param string|false $identifier Uses file.ini[BinaryFileSettings]Handler if false
     * @return eZBinaryFileHandler
     */
    static function instance( $identifier = false )
    {
        if ( $identifier === false )
        {
            $fileINI = eZINI::instance( 'file.ini' );
            $identifier = $fileINI->variable( 'smileBinaryFileSettings', 'Handler' );
        }
       
        $instance =& $GLOBALS['eZBinaryFileHandlerInstance-' . $identifier];
        
        if ( !isset( $instance ) )
        {
            $optionArray = array( 'iniFile'     => 'file.ini',
                                  'iniSection'  => 'smileBinaryFileSettings',
                                  'iniVariable' => 'Handler'  );

            $options = new ezpExtensionOptions( $optionArray );
     
            $instance = eZExtension::getHandlerClass( $options );

            if( $instance === false )
            {
                eZDebug::writeError( "Could not find binary file handler '$identifier'", __METHOD__ );
            }
        }

        return $instance;
    }

    /// \privatesection
    public $Info;
}

?>
