<?php
//
// Definition of smileCollectionBinaryFile class
//
// Created on: <30-Apr-2002 16:47:08 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: eZ Proprietary Use License v1.0
// NOTICE: >
//   This source file is part of the eZ Publish (tm) CMS and is
//   licensed under the terms and conditions of the eZ Proprietary
//   Use License v1.0 (eZPUL).
// 
//   A copy of the eZPUL was included with the software. If the
//   license is missing, request a copy of the license via email
//   at eZPUL-v1.0@ez.no or via postal mail at
//     Attn: Licensing Dept. eZ Systems AS, Klostergata 30, N-3732 Skien, Norway
// 
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
  \class smileCollectionBinaryFile smileCollectionBinaryFile.php
  \ingroup eZDatatype
  \brief The class smileCollectionBinaryFile handles registered binaryfiles

*/

class smileCollectionBinaryFile extends eZPersistentObject
{
    function smileCollectionBinaryFile( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {

        static $definition = array( 'fields' => array( 
                                        'informationcollection_id' => array( 'name' => 'informationCollectionID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZInformationCollection',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                        'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                'datatype' => 'integer',
                                                'default' => 0,
                                                'required' => true,
                                                'foreign_class' => 'eZContentObjectAttribute',
                                                'foreign_attribute' => 'id',
                                                'multiplicity' => '1..*' ),
                                         'filename' =>  array( 'name' => 'Filename',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         'original_filename' =>  array( 'name' => 'OriginalFilename',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                         'mime_type' => array( 'name' => 'MimeType',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         'download_count' => array( 'name' => 'DownloadCount',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ) ),
                      'keys' => array( 'informationcollection_id','contentobject_attribute_id' ),
                      'relations' => array( 'informationcollection_id' => array(   'class' => 'eZInformationCollection',
                                                                                   'field' => 'id' ),
                                             'contentobject_attribute_id' => array( 'class' => 'eZContentObjectAttribute',
                                                                                    'field' => 'id' )
                      ),
                      "function_attributes" => array( 'filesize' => 'fileSize',
                                                      'filepath' => 'filePath',
                                                      'mime_type_category' => 'mimeTypeCategory',
                                                      'mime_type_part' => 'mimeTypePart' ),
                      'class_name' => 'smileCollectionBinaryFile',
                      'name' => 'smilecollectionbinaryfile' );
        return $definition;
    }


    function fileSize()
    {
        $fileInfo = $this->storedFileInfo();

        $file = eZClusterFileHandler::instance( $fileInfo['filepath'] );
        if ( $file->exists() )
        {
            $stat = $file->stat();
            return $stat['size'];
        }

        return 0;
    }

    function filePath()
    {
        $fileInfo = $this->storedFileInfo();
        return $fileInfo['filepath'];
    }

    function mimeTypeCategory()
    {
        $types = explode( '/', eZPersistentObject::attribute( 'mime_type' ) );
        return $types[0];
    }

    function mimeTypePart()
    {
        $types = explode( '/', eZPersistentObject::attribute( 'mime_type' ) );
        return $types[1];
    }

    static function create( $informationcollectionId,$contentObjectAttributeID )
    {
        $row = array( 'informationcollection_id'   => $informationcollectionId,
                      'contentobject_attribute_id' => $contentObjectAttributeID,
                      'filename' => '',
                      'original_filename' => '',
                      'mime_type' => ''
                      );
        return new smileCollectionBinaryFile( $row );
    }
/**
 * 
 * @param unknown $informationcollectionId
 * @param unknown $contentObjectAttributeID
 * @param string $asObject
 * @return Ambigous <NULL, unknown>
 */
    static function fetch( $informationcollectionId, $contentObjectAttributeID, $asObject = true )
    {
            return eZPersistentObject::fetchObject( smileCollectionBinaryFile::definition(),
                                                    null,
                                                    array( 'informationcollection_id'    => $informationcollectionId,
                                                           'contentobject_attribute_id' => $contentObjectAttributeID
                                                         ),
                                                       $asObject);
    }
    /**
     * 
     * @param unknown $informationcollectionId
     * @param unknown $contentObjectAttributeID
     * @param string $asObject
     * @return Ambigous <NULL, unknown>
     */
    static function fetchByCollectionID( $informationcollectionId, $asObject = true )
    {
        return eZPersistentObject::fetchObject( smileCollectionBinaryFile::definition(),
                                                    null,
                                                    array( 'informationcollection_id'    => $informationcollectionId,),
                                                       $asObject);
    }
    /**
     * 
     * @param unknown $contentObjectAttributeID
     * @param string $asObject
     * @return Ambigous <NULL, unknown>
     */
    static function fetchByAttributeID( $contentObjectAttributeID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( smileCollectionBinaryFile::definition(),
                                                    null,
                                                    array( 
                                                           'contentobject_attribute_id' => $contentObjectAttributeID
                                                         ),
                                                       $asObject);
    }
    /**
     * 
     * @param unknown $filename
     * @param string $asObject
     * @return Ambigous <NULL, unknown, multitype:unknown >
     */
    static function fetchByFileName( $filename, $asObject = true )
    {
            return eZPersistentObject::fetchObjectList( smileCollectionBinaryFile::definition(),
                                                        null,
                                                        array( 'filename' => $filename ),
                                                        null,
                                                        null,
                                                        $asObject );

    }

    static function removeByID( $informationcollectionId,$contentObjectAttributeID )
    {
            eZPersistentObject::removeObject( smileCollectionBinaryFile::definition(),
                                              array(
                                              'informationcollection_id'   => $informationcollectionId,
                                              'contentobject_attribute_id' => $contentObjectAttributeID
                                              ) );
    }
    
    static function removeByCollectionID( $informationcollectionId )
    {
        eZPersistentObject::removeObject( smileCollectionBinaryFile::definition(),
        array(
        'informationcollection_id'   => $informationcollectionId
        ) );
    }
    
    static function removeByAttributeID( $contentObjectAttributeID )
    {
        eZPersistentObject::removeObject( smileCollectionBinaryFile::definition(),
        array(
        'contentobject_attribute_id' => $contentObjectAttributeID
        ) );
    }
    /*!
     \return the medatata from the binary file, if extraction is supported
      for the current mimetype.
    */
    function metaData()
    {
        $metaData = "";
        $binaryINI = eZINI::instance( 'binaryfile.ini' );

        $handlerSettings = $binaryINI->variable( 'HandlerSettings', 'MetaDataExtractor' );

        if ( isset( $handlerSettings[$this->MimeType] ) )
        {
            // Check if plugin exists
            if ( eZExtension::findExtensionType( array( 'ini-name' => 'binaryfile.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-directory' => false,
                                                    'type' => $handlerSettings[$this->MimeType],
                                                    'subdir' => 'plugins',
                                                    'extension-subdir' => 'plugins',
                                                    'suffix-name' => 'parser.php' ),
                                             $out ) )
            {
                $filePath = $out['found-file-path'];
                include_once( $filePath );
                $class = $handlerSettings[$this->MimeType] . 'Parser';

                $parserObject = new $class( );
                $fileInfo = $this->storedFileInfo();

                $file = eZClusterFileHandler::instance( $fileInfo['filepath'] );
                if ( $file->exists() )
                {
                    $fetchedFilePath = $file->fetchUnique();
                    $metaData = $parserObject->parseFile( $fetchedFilePath );
                    $file->fileDeleteLocal( $fetchedFilePath );
                }
            }
            else
            {
                eZDebug::writeWarning( "Plugin for $this->MimeType was not found", 'smileCollectionBinaryFile' );
            }
        }
        else
        {
            eZDebug::writeWarning( "Mimetype $this->MimeType not supported for indexing", 'smileCollectionBinaryFile' );
        }

        return $metaData;
    }

    function storedFileInfo()
    {
        $fileName = $this->attribute( 'filename' );
        $mimeType = $this->attribute( 'mime_type' );
        $originalFileName = $this->attribute( 'original_filename' );
        $storageDir = eZSys::storageDirectory();
        list( $group, $type ) = explode( '/', $mimeType );
        $filePath = $storageDir . '/original/' . $group . '/' . $fileName;
        return array( 'filename' => $fileName,
                      'original_filename' => $originalFileName,
                      'filepath' => $filePath,
                      'mime_type' => $mimeType );
    }
    /*!
     \static
    Removes all files attributes for collected information.
    \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
    the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        //delete information collected binary files
        $rows = $db->arrayQuery( "SELECT contentobject_attribute_id  FROM smilecollectionbinaryfile");

        if($rows)
        {
            foreach ($rows as $row)
            {
                $contentobjectAttributes =  eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                        null,
                        array("id" => $row['contentobject_attribute_id'] ),
                        null,
                        null,
                        true );

                foreach ( $contentobjectAttributes as $contentobjectAttribute )
                {
                    $dataType = $contentobjectAttribute->dataType();
                    if ( !$dataType )
                        continue;
                     
                    if(method_exists($dataType,'deleteStoredInformationCollectionAttribute'))
                        $dataType->deleteStoredInformationCollectionAttribute( $contentobjectAttribute );
                }
            }
        }
        $db->query( "DELETE FROM smilecollectionbinaryfile" );
    }
    
    public $ContentObjectAttributeID;
    public $Filename;
    public $OriginalFilename;
    public $MimeType;
}

?>
