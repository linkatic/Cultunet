<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
	<div id="lyftenbloggie">
		<blog:foreach item="entry" from="{entries}" key="prod">
		<div id="post-{prod_pos}" class="blogentry">
		<table style="width:100%;padding:0;margin:0;">
		<tr>
		<blog:if test="{PARAM.left_object|image}">
		<td valign="top" style="width:{PARAM.leftcol_width|auto};">
			<div class="blogentry-image rounded">
				<a href="{entry.readmore_link}" title="{entry.title}">
					<blog:value select="{entry.mainImage}" />
				</a>
			</div>
		</td>
		</blog:if>
		<td valign="top">	
			<div class="blogentry-content">
				<h1 class="blogentry-heading">
					<a href="{entry.readmore_link}" rel="bookmark" title="{entry.title}"><blog:value select="{entry.title}" /></a>
					<blog:if test="{entry.editable}">
					<span style="margin-left:10px;font-size:10px;"><a href="{entry.editable}" class="">[<blog:value select="{JTEXT.EDIT FRONTENT}" />]</a></span>
					</blog:if>
				</h1>

				<div class="blogentry-head">
					<span class="date"><blog:value select="{entry.created}" /></span>
					<span class="comments"><a href="{entry.readmore_link}#comment" title="{entry.title}"><blog:value select="{entry.comcount}" /> <blog:value select="{JTEXT.COMMENTS}" /></a></span>
					<span class="cat"><blog:value select="{entry.category}" /></span>
					<span class="author"><blog:value select="{JTEXT.SPACE BY}" /> <blog:value select="{entry.author}" /></span>
				</div>

				<div class="blogentry-text">
					<blog:value select="{entry.text}" />
				</div>

				<div class="blogentry-bottom">
					<blog:if test="{entry.tags}">
					<span class="tags">
						<blog:value select="{entry.tags}" />
					</span>
					</blog:if>
					<a href="{entry.readmore_link}" class="more-link"><blog:value select="{JTEXT.READ MORE}" /></a>
				</div>
			</div>
		</td>
		</tr>
		</table>
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