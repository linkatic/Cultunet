<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="lyftenbloggie" class="lyftenbloggie">
	<div class="blogcontent">
		<div class="lbPost" id="lbPost">
			<blog:value select="{entry.event.afterDisplayTitle}" />
			<div class="lbPost_full">
				<h2 class="contentheading clearfix"><blog:value select="{entry.title}" />
				<blog:if test="{entry.state} = '-1'">
				<span class="entry-state">[<blog:value select="{JTEXT.UNPUBLISHED}" />]</span>
				</blog:if>
				</h2>
				<div class="lbPost_meta">
					<span class="first"><blog:value select="{entry.created}" /></span>
				<blog:if test="{entry.trackback}">
					<span> <a href="{entry.trackback}" rel="trackback"><blog:value select="{JTEXT.TRACKBACK}" /></a></span>
				</blog:if>
					<span> <blog:value select="{entry.category}" /></span>
					<span class="last">
				<blog:if test="{entry.author.username}">
					<blog:value select="{JTEXT.SPACE BY}" /> <a href="{entry.author_url}"><blog:value select="{entry.author.username}" /></a>
				</blog:if>
					</span>
				<blog:if test="{entry.editable}">
					<span class="editEnrty"><a href="{entry.editable}" class=""><blog:value select="{JTEXT.EDIT FRONTENT}" /></a>
					<blog:if test="{entry.publishable}">
					<blog:value select="{entry.publishable}" />
					</blog:if>
					<blog:if test="{entry.publishup}">
					<span class="entry-state pub">[<blog:value select="{entry.publishup}" />]</span>
					</blog:if>
					</span>
				</blog:if>
				</div>
				<div id="component" class="entry-content">
				<blog:value select="{entry.event.beforeDisplayContent}" />
				<blog:value select="{entry.text}" />
				<blog:value select="{entry.event.afterDisplayContent}" />
				</div>
				<div class="clear"> </div>
			<blog:if test="{entry.bookmarks[button]}">
				<div class="bookmarks">
					<blog:value select="{entry.bookmarks[button]}" />
				</div>
			</blog:if>
			</div>
			<div class="clear"> </div>
			<blog:if test="{entry.tags}">
			<p class="entry-tags"><b><blog:value select="{JTEXT.TAGS}" /></b>: <blog:value select="{entry.tags}" /></p>
			</blog:if>

			<div class="clear"> </div>
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
</div>
<div class="clear"> </div>
<blog:if test="{SETTING.powerby|1}">
<div id="blog-footer"><blog:value select="{JTEXT.POWERED BY}" /> <a href="http://www.lyften.com" target="_blank">LyftenBloggie</a></div>
</blog:if>
</td>
</tr>
</tbody></table>