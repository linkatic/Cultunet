<blog:if test="{entry.comcount} or {entry.allowComments}">
	<div id="lb_comment_container">
		<a name="comment"> </a>
		<h3><blog:value select="{JTEXT.COMMENTS}" /> (<blog:value select="{totalcoms}" />)</h3>
		<div class="lb_comments">
		<blog:foreach item="comment" from="{commentsData}" key="prod">
			<div class="lb_comoment_content">
				<div class="lb_com_header_block">
					<div class="lb_com_header">
						<div class="lb_actions">
							<span id="lb-loader-{comment.id}" class="lb_loader"><img src="{SYSTEM.blog_assets}/images/loader_sm.gif" alt="" /></span>
							<span class="lb_votetotal"><blog:value select="{comment.karma|formatKarma}" /></span>
							<span id="rate-result-{comment.id}" class="lb_result" style="display:none;"> </span>
							<a id="rate-up-{comment.id}" class="lb_voteup" href="javascript:void(0);" onclick="blogajax.call('vote', {{ id: '{comment.id}', vote:'1' }}, 'lb-loader-{comment.id}');" title="Vote up"><span>Vote up</span></a>
							<a id="rate-dwn-{comment.id}" class="lb_votedown" href="javascript:void(0);" onclick="blogajax.call('vote', {{ id: '{comment.id}', vote:'0' }}, 'lb-loader-{comment.id}');" title="Vote down"><span>Vote down</span></a>
							<a id="report-{comment.id}" class="lb_report" href="{SYSTEM.base_url}index.php?option=com_lyftenbloggie&task=ajax&act=report&&param[id]={comment.id}" onclick="displayMessage(this,false);return false" title="{JTEXT.REPORT AS INAPPROPRIATE}"><span>Report</span></a>
						</div>
						<blog:if test="{comment.author_url}">
							<a href="{comment.author_url}" target="_blank" class="lb_avatar"><img src="{comment.avatar}" alt="" class="lb_user_ava" /></a>
							<blog:else>
								<img src="{comment.avatar}" alt="" class="lb_user_ava" />
							</blog:else>
						</blog:if>
						<p class="lb_user">
							<blog:if test="{comment.author_url}">
								<a href="{comment.author_url}" target="_blank" class="lb_avatar"><blog:value select="{comment.poster}" /></a>
								<blog:else>
									<blog:value select="{comment.poster}" />
								</blog:else>
							</blog:if>
							<em class="lb_usertime"><blog:jdate date="{comment.date}" /></em>
						</p>
					</div>
				</div>
				<div class="lb_comoment_txt" style="padding:0 10px 10px 10px;"><blog:value select="{comment.content}" /></div>
			</div>
		</blog:foreach>
		</div>

		<blog:if test="{pageNav.limit} &lt; {pageNav.total}">
			<blog:if test="{SYSTEM.view} = 'comments'">
			<div class="page-nav">
				<dl>
					<dt><blog:value select="{pageNav.getPagesCounter}" /></dt>
					<dd><blog:value select="{pageNav.getPagesLinks}" /></dd>
				</dl>
				<div class="clear"> </div>
			</div>
			<blog:else>
			<div class="page-nav">
				<dl>
					<dd>
					<blog:jroute url="index.php?&option=com_lyftenbloggie&view=comments{entry.archive}&id={entry.slug}"><blog:value select="{JTEXT.VIEW ALL COMMENTS}" /></blog:jroute></dd>
				</dl>
				<div class="clear"> </div>
			</div>
			</blog:else>
			</blog:if>
		</blog:if>

		<blog:commentbox id="{entry.id}" allow="{entry.allowComments}" total="{totalcoms}" />
 
	</div>
</blog:if>