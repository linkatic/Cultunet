<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="lyftenbloggie" class="lyftenbloggie">
<blog:value select="{entry.event.afterDisplayTitle}" />
<div class="postinfo">
	<h3 class="blogentry-title"><blog:value select="{entry.title}" /></h3>
	<p>Posted in <blog:value select="{entry.category}" /> on <blog:value select="{entry.created}" /><blog:value select="{JTEXT.SPACE BY}" /> <blog:value select="{entry.author.username}" /></p>
</div>

<div class="blogentry">
<blog:if test="{entry.bookmarks[badge]}">
<div class="bookmarkbadge"><blog:value select="{entry.bookmarks[badge]}" /></div>
</blog:if>
<blog:value select="{entry.event.beforeDisplayContent}" />
<blog:value select="{entry.text}" />
<blog:value select="{entry.event.afterDisplayContent}" />
</div>
<div class="clear"></div>

<blog:if test="{entry.tags}">
<span class="blogentry-tags">
	<blog:value select="{JTEXT.TAGS}" /> <blog:value select="{entry.tags}" />
</span>
</blog:if>

<blog:if test="{entry.bookmarks[button]}">
<div class="bookmarks">
<blog:value select="{entry.bookmarks[button]}" />
</div>
</blog:if>
<div class="postdata">

	<div class="blogentry-options">
		<blog:if test="{entry.allowComments}">
			<a href="#comment"><blog:value select="{JTEXT.LEAVE A COMMENT}" /></a> &#149; 
		</blog:if>

		<blog:if test="{entry.trackback}">
		<a href="{entry.trackback}" class="trackback-link"><blog:value select="{JTEXT.TRACKBACK}" /></a>
		</blog:if>

		<blog:if test="{entry.editable}">
		&#149; <a href="{entry.editable}"><blog:value select="{JTEXT.EDIT FRONTENT}" /></a>
		</blog:if>
	</div>
</div>
<div class="clear"></div>

	<blog:if test="{entry.allowComments}">
		<blog:comments id="{entry.id}" title="{entry.title}" />
	</blog:if>

</div>
<div class="clear"> </div>
<blog:if test="{SETTING.powerby|1}">
<div id="blog-footer"><blog:value select="{JTEXT.POWERED BY}" /> <a href="http://www.lyften.com" target="_blank">LyftenBloggie</a></div>
</blog:if>
</td>
</tr>
</tbody></table>