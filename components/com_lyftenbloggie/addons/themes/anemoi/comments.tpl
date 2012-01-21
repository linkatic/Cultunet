<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="lyftenbloggie" class="lyftenbloggie">
	<div class="blogcontent">
		<div class="lbpost comments" id="lbpost">
			<blog:if test="{SETTING.typeComments|default} = 'default'">
				<blog:comments id="{entry.id}" title="{entry.title}" />
			</blog:if>
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
