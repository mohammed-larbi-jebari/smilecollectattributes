{let matrix=$attribute.content}
<table class="list" cellspacing="0">
<tr>
{section var=ColumnNames loop=$matrix.columns.sequential}
<th>{$ColumnNames.item.name}</th>
{/section}
</tr>
{section var=Rows loop=$matrix.rows.sequential sequence=array( bglight, bgdark )}
<tr class="{$Rows.sequence}">
    {section var=Columns loop=$Rows.item.columns}
    <td>{$Columns.item|wash( xhtml )}</td>
    {/section}
</tr>
{/section}
</table>
{/let}