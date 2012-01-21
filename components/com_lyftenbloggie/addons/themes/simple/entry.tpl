<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="lyftenbloggie" class="lyftenbloggie">
	<div class="blogentry blogentry-no-pic">
		<blog:value select="{entry.event.afterDisplayTitle}" />
		<div class="blogentry-content ">
			<h1 class="blogentry-heading">
				<a href="{entry.url}" rel="bookmark" title="{entry.title}"><blog:value select="{entry.title}" /></a>
				<blog:if test="{entry.editable}">
				<span style="margin-left:10px;font-size:10px;"><a href="{entry.editable}" class="">[<blog:value select="{JTEXT.EDIT FRONTENT}" />]</a></span>
				</blog:if>
			</h1>

			<div class="blogentry-head">
				<span class="date"><blog:value select="{entry.created}" /></span>
				<span class="comments"><a href="#comment" title="{entry.title}"><blog:value select="{entry.comcount}" /> <blog:value select="{JTEXT.COMMENTS}" /></a></span>
				<span class="cat"><blog:value select="{entry.category}" /></span>
				<span class="author"><blog:value select="{JTEXT.SPACE BY}" /> <blog:value select="{entry.author.username}" /></span>
			</div>

			<div class="blogentry-text">
				<blog:value select="{entry.event.beforeDisplayContent}" />
				<blog:value select="{entry.text}" />
				<blog:value select="{entry.event.afterDisplayContent}" />
			</div>

			<blog:if test="{entry.tags}">
			<div class="blogentry-bottom">
				<span class="tags">
					<blog:value select="{entry.tags}" />
				</span>
			</div>
			</blog:if>
		</div>
	</div>

	<blog:if test="{author.about}">
	<div class="blogentry" id="author-box">
		<table style="width:100%;padding:0;margin:0;">
		<tr>
		<td valign="top" align="center">
		<div class="blogavatar">
			<img style="opacity: 1; visibility: visible;" alt="" src="{author.avatar}" height="80" width="80" />
			<a href="{entry.author_url}" alt="{JTEXT.VIEW ENTRIES BY} {author.username}" title="{JTEXT.VIEW ENTRIES BY} {author.username}"><blog:value select="{author.username}" /></a>				
		</div>
		</td>
		<td valign="top">	
		<div class="author-info">
			<h3 class="about-title"><blog:value select="{JTEXT.ABOUT THE AUTHOR}" /></h3>
			<blog:value select="{author.about}" />
		</div>
		<blog:if test="{author.feeds}">
		<span class="authorFeed"><blog:value select="{author.feeds}" /></span>
		</blog:if>
		</td>
		</tr>
		</table>
	</div>
	</blog:if>
	<div class="clear"> </div>

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