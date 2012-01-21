<div>
<div class="moduleblog-title"><h3>+ POST</h3></div>
<div class="border-container bg-container">
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="lyftenbloggie" class="lyftenbloggie">
	<div class="blogcontent entry">
		<div class="section" style="border:none;">
			<h1 class="entry-title contentheading"><blog:value select="{entry.title}" />
			<blog:if test="{entry.state} = '-1'">
			<span class="entry-state">[<blog:value select="{JTEXT.UNPUBLISHED}" />]</span>
			</blog:if>
			<blog:if test="{entry.publishup}">
			<span class="entry-state pub">[<blog:value select="{entry.publishup}" />]</span>
			</blog:if>
			</h1>
			<div  class="entry-subtitle">
				<blog:if test="{entry.trackback}">
					<a href="{entry.trackback}" rel="trackback"><blog:value select="{JTEXT.TRACKBACK}" /></a>&nbsp;<span class="sep">/</span>&nbsp;
				</blog:if>
				<blog:value select="{entry.created}" /> <span class="sep">/</span> <blog:value select="{JTEXT.POSTED IN}" /> <span class="catpost"><blog:value select="{entry.category}" /></span>
			</div>
			<blog:value select="{entry.event.afterDisplayTitle}" />

			<blog:if test="{entry.bookmarks[button]}">
			<div class="right" style="padding-top: 15px;">
			<blog:value select="{entry.bookmarks[button]}" />
			</div>
			</blog:if>
			<ul class="entry-options" style="margin: 10px 0 0 0;">
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
		<div class="post-content">
			<blog:value select="{entry.event.beforeDisplayContent}" />
			<blog:value select="{entry.text}" />
			<blog:value select="{entry.event.afterDisplayContent}" />
		</div>
		<blog:if test="{author.about}">
		<div class="authbox">
			<div id="authorpic">
				<img alt="" src="{author.avatar}" />
				<a href="{entry.author_url}"><blog:value select="{JTEXT.VIEW ENTRIES}" /></a>
			</div>
			<div class="postauthorinfo">
				<h2 class="authorname"><blog:value select="{author.username}" /></h2>
				<div class="clear"> </div>
				<div>
					<p class="authordesc"><blog:value select="{author.about}" /></p>
					<blog:if test="{author.feeds}">
					<span class="authorFeed"><blog:value select="{author.feeds}" /></span>
					</blog:if>
				</div>
			</div>
		</div>
		</blog:if>
		<div class="clear"> </div>
		<blog:comments id="{entry.id}" title="{entry.title}" />
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