<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
	<div id="lyftenbloggie" class="lyftenbloggie">
		<div class="blogcontent" style="">
		<blog:foreach item="entry" from="{entries}" key="prod">
			<div class="lbPost" id="post-{entry.id}{prod_pos}">
				<blog:if test="{entry.mainImage}">
				<div class="lbPost_left" style="width:{PARAM.leftcol_width|200px};">
					<blog:value select="{entry.mainImage}" />
					<blog:if test="{entry.comcount} or {entry.allowComments}">
					<div class="commentsmain">
						<div class="speechbubble">
							<p><blog:value select="{entry.comcount}" /></p>
						</div>
						<div class="commentstext">
							<p><blog:value select="{JTEXT.COMMENTS}" /></p>
						</div>
						<div class="commentsadd">
						<blog:if test="{entry.allowComments}">
							<p><a href="{entry.readmore_link}#comment" title="{entry.title}"><blog:value select="{JTEXT.ADD}" /></a></p>
							<blog:else>
							<p><a href="{entry.readmore_link}#comment" title="{entry.title}" class="noComment"> </a></p>
							</blog:else>
						</blog:if>
						</div>
					</div>
					</blog:if>
				</div>
				</blog:if>
				<div class="lbPost_right" style="padding-left:{PARAM.leftcol_width|200px};">	
					<h2><a href="{entry.readmore_link}" rel="bookmark" title="{entry.title}"><blog:value select="{entry.title}" /></a>
					<blog:if test="{entry.state} = '-1'">
					<span class="entry-state">[<blog:value select="{JTEXT.UNPUBLISHED}" />]</span>
					</blog:if>
					<blog:if test="{entry.publishup}">
					<span class="entry-state pub">[<blog:value select="{entry.publishup}" />]</span>
					</blog:if>
					</h2>
					<div class="clear"> </div>
					<div class="lbPost_meta">
						<span class="first"><blog:value select="{entry.created}" /></span>
						<span> <blog:value select="{entry.category}" /></span>
						<span class="last">
					<blog:if test="{entry.author}">
						<blog:value select="{JTEXT.SPACE BY}" /> <a href="{entry.author_url}"><blog:value select="{entry.author}" /></a>
					</blog:if>
						</span>
					<blog:if test="{entry.editable}">
						<span class="editEnrty"><a href="{entry.editable}" class=""><blog:value select="{JTEXT.EDIT FRONTENT}" /></a>
						<blog:if test="{entry.publishable}">
						<blog:value select="{entry.publishable}" />
						</blog:if>
						</span>
					</blog:if>
					</div>
					<div class="clear"> </div>
					<div id="component" class="entry-content">
					<blog:value select="{entry.text}" />
					</div>
					<div class="clear"> </div>
				<blog:if test="{entry.readmore}">
					<p class="read-more"><a href="{entry.readmore_link}"><blog:value select="{JTEXT.READ MORE}" /></a></p>
				</blog:if>
				<blog:if test="{entry.tags}">
					<p class="entry-tags"><b><blog:value select="{JTEXT.TAGS}" /></b>: <blog:value select="{entry.tags}" /></p>
				</blog:if>
				<blog:if test="{entry.bookmarks[button]}">
					<div class="bookmarks">
				<blog:value select="{entry.bookmarks[button]}" />
					</div>
				</blog:if>
				</div>
			</div>
			<div class="clear"> </div>
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