{default attribute_base=ContentObjectAttribute}


{let matrix=cond(is_set($#collection_attributes[$attribute.id]),$#collection_attributes[$attribute.id].content,$attribute.content)}

{* Matrix. *}
{set-block scope=root variable=table_id}
ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_rows
{/set-block}

{section show=$matrix.rows.sequential}
<table id="{$table_id|trim()}" class="list" cellspacing="0">

<tr>
    <th class="tight">&nbsp;</th>
    {section var=ColumnNames loop=$matrix.columns.sequential}<th>{$ColumnNames.item.name}</th>{/section}
</tr>

{section var=Rows loop=$matrix.rows.sequential sequence=array( bglight, bgdark )}
<tr class="{$Rows.sequence}">

{* Remove. *}
{set-block scope=root variable=checkbox_class}
ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}
{/set-block}
<td><input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_remove_{$Rows.index}" class="ezcc-{$attribute.object.content_class.identifier} {$checkbox_class|trim}" type="checkbox" name="{$attribute_base}_data_matrix_remove_{$attribute.id}[]" value="{$Rows.index}" title="{'Select row for removal.'|i18n( 'design/standard/content/datatype' )}" /></td>

{* Custom columns. *}
{section var=Columns loop=$Rows.item.columns}
<td><input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_matrix_cell_{$Rows.index}_{$Columns.index}" class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" name="{$attribute_base}_smilematrix_cell_{$attribute.id}[]" value="{$Columns.item|wash( xhtml )}" /></td>
{/section}


<td>
  <input  class="ezcc-{$attribute.object.content_class.identifier}_remove_row ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}_remove_row remove_row" type="button" name="{$attribute.id}_remove_row" value="{'Remove'|i18n( 'design/standard/content/datatype' )}" title="{'Remove row from the matrix.'|i18n( 'design/standard/content/datatype' )}" />
</td>
</tr>
{/section}
</table>
{section-else}
<p>{'There are no rows in the matrix.'|i18n( 'design/standard/content/datatype' )}</p>
{/section}


{* Buttons. *}
{set-block scope=root variable=id_remove_rows}
ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_remove_selected
{/set-block}

{if $matrix.rows.sequential}
<input id="{$id_remove_rows|trim()}" class="button ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="submit" name="ActionNewMatrixRow[{$attribute.id}_remove_selected]" value="{'Remove selected'|i18n( 'design/standard/content/datatype' )}" title="{'Remove selected rows from the matrix.'|i18n( 'design/standard/content/datatype' )}" />
{else}
<input class="button-disabled" type="submit" name="ActionNewMatrixRow[{$attribute.id}_remove_selected]" value="{'Remove selected'|i18n( 'design/standard/content/datatype' )}" disabled="disabled" />
{/if}
&nbsp;&nbsp;
{let row_count=sub( 40, count( $matrix.rows.sequential ) ) index_var=0}
{if $row_count|lt( 1 )}
        {set row_count=0}
{/if}

{set-block scope=root variable=add_count_id}
ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_add_count
{/set-block}
<select id="{$add_count_id|trim}" class="matrix_cell ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" name="{$attribute_base}_data_matrix_add_count_{$attribute.id}" title="{'Number of rows to add.'|i18n( 'design/standard/content/datatype' )}" >
    <option value="1">1</option>
    {section loop=$row_count}
        {set index_var=$index_var|inc}
        {delimiter modulo=5}
           <option value="{$index_var}">{$index_var}</option>
        {/delimiter}
   {/section}
</select>

{set-block scope=root variable=id_add_nex_row}
ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_new_row
{/set-block}

<input id="{$id_add_nex_row|trim()}" class="button ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="submit" name="ActionNewMatrixRow[{$attribute.id}_new_row]" value="{'Add rows'|i18n('design/standard/content/datatype')}" title="{'Add new rows to the matrix.'|i18n( 'design/standard/content/datatype' )}" />
{/let}


{/let}
{/default}

<script>
jQuery(function() 
{literal}{{/literal}
 var id_add_nex_row = '#{$id_add_nex_row|trim()}';
 var id_remove_rows = '#{$id_remove_rows|trim()}';
 var table_id       = '#{$table_id|trim()}';
 var checkbox_class = '.{$checkbox_class|trim}';
 var add_count_id   = '#{$add_count_id|trim}';
{literal}
    function smile_remove_row()
    {
           //remove row
           jQuery('.remove_row').bind('click', function(event)
           {
             jQuery(this).parent().parent().remove();
             return false;
           });
    }
       elementToClone   = jQuery(table_id).find("tbody").find("input:first").parent().parent().html();

       // add row to table
       jQuery(id_add_nex_row).bind('click', function(event)
       {
            var $tableBody = jQuery(table_id).find("tbody");
            var add_count = jQuery(add_count_id).val();
            
            for(i =0;i < add_count;i++)
            {
             $trLast      = $tableBody.find("tr:last");
             $trLast.after('<tr>'+elementToClone+'</tr>');
             $tableBody.find('tr:last input[type="text"]').val('');
             //remove row
             smile_remove_row();
            }
            return false;
       });
       //remove rows 
       jQuery(id_remove_rows).bind('click', function(event)
       {
         jQuery('input'+checkbox_class).each(function () {
           if (this.checked) {
               jQuery(this).parent().parent().remove();
           }
         });
         return false;
       });
       //remove row
       smile_remove_row();
       
});
{/literal}
</script>

