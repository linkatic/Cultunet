<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
	<div id="lyftenbloggie">
	<blog:foreach item="entry" from="{entries}">
	<div class="lbPost" id="post-{prod_pos}">
		<div class="postinfo">
		<h3 class="blogentry-title"><a href="{entry.readmore_link}" rel="bookmark" title="{entry.title}"><blog:value select="{entry.title}" /></a>
		<blog:if test="{entry.state} = '-1'">
		<span class="entry-state">[<blog:value select="{JTEXT.UNPUBLISHED}" />]</span>
		</blog:if>
		</h3>
		<p>Posted in <blog:value select="{entry.category}" /> on <blog:value select="{entry.created}" /><blog:value select="{JTEXT.SPACE BY}" /> <blog:value select="{entry.author}" /></p>
		</div>
		<div class="blogentry">
			<blog:value select="{entry.text}" />
		</div>
		<div class="clear"> </div>
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
				<blog:if test="{entry.readmore}">
				<div class="read-more"><a href="{entry.readmore_link}" class="more-link"><blog:value select="{JTEXT.READ MORE}" /></a></div>
				</blog:if>

				<a href="{entry.readmore_link}#comment" title="{entry.title}"><blog:value select="{entry.comcount}" /> <blog:value select="{JTEXT.COMMENTS}" /></a>
				<blog:if test="{entry.trackback}">
				&#149; <a href="{entry.trackback}" class="trackback-link"><blog:value select="{JTEXT.TRACKBACK}" /></a>
				</blog:if>

				<blog:if test="{entry.editable}">
				&#149; <a href="{entry.editable}"><blog:value select="{JTEXT.EDIT FRONTENT}" /></a>
				</blog:if>
				<blog:if test="{entry.publishable}">
				&#149; <blog:value select="{entry.publishable}" />
				</blog:if>
			</div>
		</div>	
	</div>
	</blog:foreach>

	<blog:value select="{message}" />
	<blog:if test="{pageNav.limit} &lt; {pageNav.total}">
		<div class="page-nav">
			<dl>
				<dt><blog:value select="{pageNav.getPagesCounter}" /></dt>
				<dd><blog:value select="{pageNav.getPagesLinks}" /></dd>
			</dl>
			<div class="clear"></div>
		</div>
	</blog:if>
</div>
<div class="clear"></div>
	<blog:if test="{SETTING.powerby|1}">
	<div id="blog-footer"><blog:value select="{JTEXT.POWERED BY}" /> <a href="http://www.lyften.com" target="_blank">LyftenBloggie</a></div>
	</blog:if>
</td>
</tr>
</tbody></table>