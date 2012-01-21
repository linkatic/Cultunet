<div>
<div class="moduleblog-title"><h3>+ <blog:value select="{author_blog}" /></h3></div>
<div class="border-container bg-container">
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
	<div id="lyftenbloggie" class="lyftenbloggie">
		<div class="blogcontent">
		<blog:foreach item="entry" from="{entries}">
			<div class="section">
				<div class="entry-info">
				<a href="{entry.author_url}">
					<blog:value select="{entry.mainImage}" />
					</a>
					<span class="entry-title"><a href="{entry.readmore_link}" rel="bookmark" title="{entry.title}"><blog:value select="{entry.title}" /></a>
					<blog:if test="{entry.state} = '-1'">
					<span class="entry-state">[<blog:value select="{JTEXT.UNPUBLISHED}" />]</span>
					</blog:if>
					<blog:if test="{entry.publishup}">
					<span class="entry-state pub">[<blog:value select="{entry.publishup}" />]</span>
					</blog:if>
					</span>
					<p class="entry-small"><blog:value select="{entry.created}" /> -
					<blog:if test="{entry.author}">
						<blog:value select="{JTEXT.POSTED BY}" /> <a href="{entry.author_url}"><blog:value select="{entry.author}" /></a>
					</blog:if>
					 <blog:value select="{JTEXT.POSTED IN}" /> <strong class="catpost"><blog:value select="{entry.category}" /></strong>
					</p>
				</div>
				<div id="component" class="entry-content">
				<blog:value select="{entry.text}" />
				</div>
				<blog:if test="{entry.bookmarks[button]}">
				<div class="right" style="padding-top: 0px;">
				<blog:value select="{entry.bookmarks[button]}" />
				</div>
				</blog:if>
				<ul class="entry-options" style="border-top: medium none; margin: 6px 0 0 0;">
				<blog:if test="{entry.comcount} or {entry.allowComments}">
					<li class="entry-options-comments"><strong><blog:value select="{entry.comcount}" /></strong> <blog:value select="{JTEXT.COMMENTS}" /></li>
				</blog:if>
				<blog:if test="{entry.tags}">
				<li class="entry-options-tag"><strong><blog:value select="{JTEXT.TAGS}" />:</strong> <blog:value select="{entry.tags}" /></li>
				</blog:if>
				<blog:if test="{entry.editable}">
				<li class="entry-options-actions"><strong><blog:value select="{JTEXT.ACTIONS}" />:</strong>&nbsp;&nbsp;<a href="{entry.editable}" class=""><blog:value select="{JTEXT.EDIT FRONTENT}" /></a><blog:if test="{entry.publishable}">&nbsp;&nbsp;&#149;&nbsp;&nbsp;<blog:value select="{entry.publishable}" /></blog:if>
				</li>
				</blog:if>
				</ul>
			</div>
		</blog:foreach>

		<blog:value select="{message}" />
		<blog:if test="{pageNav.limit} &lt; {pageNav.total}">
			<div class="page-nav">
				<dl>
					<dt><blog:value select="{pageNav.getPagesCounter}" /></dt>
					<dd><blog:value select="{pageNav.getPagesLinks}" /></dd>
				</dl>
				<div class="clear"> </div>
			</div>
		</blog:if>
		</div>
	</div>
	<div class="clear"> </div>
	<blog:if test="{SETTING.powerby|1}">
	<div id="blog-footer"><blog:value select="{JTEXT.POWERED BY}" /> <a href="http://www.lyften.com" target="_blank">LyftenBloggie</a></div>
	</blog:if>
</td>
</tr>
</tbody></table>
</div>
</div>