{include file="header.tpl"}
{include file="menu.tpl"}

<div id="list">
<span class="subject">Subject</span>
<span class="trackback_ping">Trackback</span>
<span class="delete">Delete</span>

{foreach from=$entries item=entry}
<div class="entry">
	<span class="subject">
		<a href="{$baseurl}/post.php?blogid={$entry->entryId}">{$entry->title}</a>
	</span>
	<span class="trackback_ping">
		<a href="{$baseurl}/sendping.php?blogid={$entry->entryId}">Ping</a>
	</span>
	<span class="delete">
		<a href="{$baseurl}/admin.php?mode=delete_entry&blogid={$entry->entryId}" onclick="return confirm('Are you sure want to delete?');">X</a>
	</span>
</div>

	{foreach from=$entry->getComments() item=comment}
	<div class="comment">
		<span class="subject">{$comment->getBody()|strip_tags|substring:70}</span>
		<span class="trackback_ping">&nbsp;</span>
		<span class="delete"><a href="{$baseurl}/admin.php?mode=delete&file={$comment->filename}" onclick="return confirm('Are you sure want to delete?');">X</a></span>
	</div>
	{/foreach}

	{foreach from=$entry->getTrackbacks() item=trackback}
	<div class="trackback">
		<span class="subject">{$trackback->title|strip_tags|substring:70}</span>
		<span class="trackback_ping">&nbsp;</span>
		<span class="delete"><a href="{$baseurl}/admin.php?mode=delete&file={$trackback->filename}" onclick="return confirm('Are you sure want to delete?');">X</a></span>
	</div>
	{/foreach}

{/foreach}
</div>

{include file="footer.tpl"}
