<?php

/**
 * File containing the smileEzfSolrDocumentFieldObjectRelation class.
 *
 * @package smilecollectattributes
 */
/**
 * The smileEzfSolrDocumentFieldObjectRelation class handles indexing and
 * querying for the smileobjectrelation and smileobjectrelationlist in eZFind.
 *
 * @package eZFind
 */
class smileEzfSolrDocumentFieldObjectRelation extends ezfSolrDocumentFieldObjectRelation
{

    public function getCollectionData()
    {
        $returnList = array();
        if($this->ContentObjectAttribute->attribute( 'data_type_string' ) == 'smileobjectrelationlist')
        {
            $content = $this->ContentObjectAttribute->content();
            foreach ( $content['relation_list'] as $relationItem )
            {
                $subObjectID = $relationItem['contentobject_id'];
                if ( !$subObjectID )
                    continue;
                $subObject = eZContentObjectVersion::fetchVersion( $relationItem['contentobject_version'], $subObjectID );
                if ( !$subObject )
                    continue;

                $returnList = array_merge( $this->getBaseList( $subObject ),
                                           $returnList );
            }
        }
        else 
        {
        	$returnList = parent::getCollectionData();
        }

        return $returnList;
    }


    /**
     * @see ezfSolrDocumentFieldBase::getFieldName()
     *
     * @todo Implement this
     */
    public static function getFieldName( eZContentClassAttribute $classAttribute, $subAttribute = null, $context = 'search' )
    {
        if($classAttribute->attribute( 'data_type_string' ) == 'smileobjectrelationlist')
        {
         
        }
        else 
        {
           return parent::getFieldName($classAttribute, $subAttribute, $context);
        }
    }


    /**
     * @see ezfSolrDocumentFieldBase::getData()
     */
    public function getData()
    {
        $contentClassAttribute = $this->ContentObjectAttribute->attribute( 'contentclass_attribute' );

        if($contentClassAttribute->attribute( 'data_type_string' ) == 'smileobjectrelationlist')
        {
            $returnArray = array();
            $content = $this->ContentObjectAttribute->content();
            
            foreach ( $content['relation_list'] as $relationItem )
            {
                $subObjectID = $relationItem['contentobject_id'];
                if ( !$subObjectID )
                    continue;
                $subObject = eZContentObjectVersion::fetchVersion( $relationItem['contentobject_version'], $subObjectID );
                if ( !$subObject )
                    continue;
            
                // 1st create aggregated metadata fields
                $metaAttributeValues = eZSolr::getMetaAttributesForObject( $subObject->attribute( 'contentobject' ) );
                foreach ( $metaAttributeValues as $metaInfo )
                {
                    $submetaFieldName = ezfSolrDocumentFieldBase::generateSubmetaFieldName( $metaInfo['name'], $contentClassAttribute );
                    if ( isset( $returnArray[$submetaFieldName] ) )
                    {
                        $returnArray[$submetaFieldName] = array_merge( $returnArray[$submetaFieldName], array( ezfSolrDocumentFieldBase::preProcessValue( $metaInfo['value'], $metaInfo['fieldType'] ) ) );
                    }
                    else
                    {
                        $returnArray[$submetaFieldName] = array( ezfSolrDocumentFieldBase::preProcessValue( $metaInfo['value'], $metaInfo['fieldType'] ) );
                    }
                }
            }
            
            $defaultFieldName = parent::generateAttributeFieldName( $contentClassAttribute,
                    self::$subattributesDefinition[self::DEFAULT_SUBATTRIBUTE] );
            $returnArray[$defaultFieldName] = $this->getPlainTextRepresentation();
            return $returnArray;
        }
        else
        {
          return parent::getData();
        }

    }
}

?>
