<?php
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Find
// SOFTWARE RELEASE: 2.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezfsolrdocumentfieldxml.php
*/

/*!
  \class ezfSolrDocumentFieldXML ezfsolrdocumentfieldobjectrelation.php
  \brief The class ezfSolrDocumentFieldObjectRelation does

*/

class smileEzfSolrDocumentFieldXML extends ezfSolrDocumentFieldXML
{

    /**
     * @see ezfSolrDocumentFieldBase::getData()
     */
    public function getData()
    {
        $contentClassAttribute = $this->ContentObjectAttribute->attribute( 'contentclass_attribute' );
        $fieldName = self::getFieldName( $contentClassAttribute );

        switch ( $contentClassAttribute->attribute( 'data_type_string' ) )
        {
            case 'smilematrix' :
            {
                $xmlData = $this->ContentObjectAttribute->attribute( 'content' )->xmlString();
            } break;

            default:
            {
                    return array( $fieldName => '' );
            } break;
        }
        $cleanedXML = $this->strip_html_tags( $xmlData );
        return array( $fieldName => $cleanedXML );
    }
}

?>
