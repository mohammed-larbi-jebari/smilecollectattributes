{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{if $attribute.content}
      <a href={concat( 'smilebinaryfile/download/', $attribute.content.informationcollection_id, '/', $attribute.content.contentobject_attribute_id , '/file/', $attribute.content.original_filename|urlencode )|ezurl}>{$attribute.content.original_filename|wash( xhtml )}</a>&nbsp;({$attribute.content.filesize|si( byte )})
{/if}