{def $matrix = $attribute.content}

<table class="list" cellspacing="0">
<tr>
{foreach $matrix.columns.sequential as $ColumnNames}
<th>{$ColumnNames.name}</th>
{/foreach}
</tr>

{foreach $matrix.rows.sequential as $Rows sequence array( bglight, bgdark ) as $style}
<tr class="{$style}">
    {foreach  $Rows.columns as $Columns}
    <td>{$Columns|wash( xhtml )}</td>
    {/foreach}
</tr>
{/foreach}
</table>
