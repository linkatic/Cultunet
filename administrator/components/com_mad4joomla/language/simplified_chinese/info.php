<?PHP
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
	* @package joomla
	* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	* Joomla! is free software. This version may have been modified pursuant
	* to the GNU General Public License, and as distributed it includes or
	* is derivative of works licensed under the GNU General Public License or
	* other free or open source software licenses.
	* See COPYRIGHT.php for copyright notices and details.
	* @copyright (C) mad4media , www.mad4media.de
	*/
// translated by baijianpeng ( http://www.joomlagate.com )
	/**  ENGLISH VERSION. NEEDS TO BE TRANSLATED */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>


<table width="944" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="400" height="309" align="left" valign="top"><img src="components/com_mad4joomla/images/mad4media-3d.png" width="400" height="309"></td>
        <td align="left" valign="top"><h3>Mad4Joomla Mailforms V <?PHP echo M4J_VERSION_NO; ?></h3>
          本组件由  Dipl. Informatiker (similar to MSc) Fahrettin Kutyol 为 Mad4Media 开发.<br>
          Mad4Media 总是以用户为中心来开发软件。我们的产品和项目总是面向用户而设计，以期获得最大的工效学成功（易用性）。除了用 Java 和 php 编程，我们还为客户提供定制的 Joomla 或者 osCommerce 扩展开发。如果您对我们的服务有兴趣，请通过我们网站与我们联系: <a href="http://www.mad4media.de" target="_blank">Mad4Media</a><br>
          <br>
          <strong>协议及承诺</strong><br>
          Mad4Joomla Mailforms 组件遵照 GNU GPL 协议发行. 我们不承诺任何质量保证或代码完整性。Mad4Media 也不会为因使用本组件而造成的损失负责。<br>
          <br>
          <strong>在本组件中使用了下列开源产品:</strong><br>
          <a href="http://www.dynarch.com/projects/calendar/" target="_blank">jsCalendar</a> - LGPL<br>
          图标来自 <a href="http://www.dryicons.com" target="_blank">dryicons.com</a> - Creative Commons Attribution 3.0 License<br>
          <a href="http://www.dhtmlgoodies.com" target="_blank">Balloontip</a> - LGPL <br>
          <br>
          <br></td>
      </tr>
    </table>

	    <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="top"><h3>关于 Mad4Joomla Mailforms</h3>
		      <p align="left">Mad4Joomla 是一个简单的表单设计组件，功能是帮助您设计 email 表单.<br />
	          本组件的设计宗旨是：更好的易用性，可按类别分类，借助模板更改外观，表单字段提供提示信息，对目标邮件地址进行特殊追踪，将表单页面与文章整合，全新的验证码引擎。因此，也可以用它设计出复杂的、庞大的联系系统。示范: Jobs, Reservation etc. </p>
		      <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" height="100%" align="left" valign="top"><h3>Mad4Joomla 官方网站</h3>
                  <p>在 Mad4Joomla 官方网站你可以获取详细的信息。
                    <br />
                    我们对任何语言的翻译都表示感谢. 你可以从下面地址下载单独的语言文件包，（在此基础上翻译）完后将你的成果发送给我们。我们将把它添加到安装包中，公开发行。<br />
                    下载地址：<a href="http://www.mad4media.de/mad4joomla-mailforms.html" target="_blank">www.mad4media.de/mad4joomla-mailforms.html
                  </a></p>
                  <p>我们将在此设置指向翻译人员网站的链接（就像下面这个）. <br />
                  <h3>翻译人员</h3>
                    English, German by <a href="www.mad4media.de" target="_blank">mad4media</a><br />
                    Simplified Chinese and Traditional Chinese by <a href="http://www.joomlagate.com" target="_blank">baijianpeng</a><br />
                  <br />
                    <br />
                    <br />
                  </p>
                </td>
              </tr>
              </table>			<h3>&nbsp;</h3></td>
            <td width="52%" align="left" valign="top"><h3>简易操作说明 <br />
            </h3>
              <p><strong>第一步:</strong><br />
                你是否需要创建类别? <br />
                例如：你可能想要发布多个工作机会，那么我们建议创建一个名为“jobs”的类别。 当创建类别时，你可以添加一些文字，这些文字将显示在类别页面的顶部。如果某个表单没有指定目标 email 地址, 就会使用所在类别的邮件地址。如果你的类别也没有指定目标 email 地址，则会使用主邮件地址（在主要设置中）.
                <br />
                <br />
                <strong>第二步:</strong><br />
              创建一个或多个模板.<br />
              你可以在基本数据区域输入一些简介文字。这是为了区分各个模板。你一定要指定表单表格的列宽度。在下一步，你就需要创建表单字段了。你可以给每一个字段输入提示信息文字，当前台访客用鼠标指向帮助图标时，就会看到气泡式的提示消息。<br />
              <br />
                <strong>第三步:</strong><br />
                创建表单.<br />
                输入表单名称，指定所属类别。如果你不想指定类别，就选择“未分类”。<br />
                接下来，你必须为此表单选择模板。如果你不指定目标 email 地址，前台提交后的内容将发送到此表单所属类别的目标邮件地址。如果该类别也没有目标邮件地址，则会发送给主邮件地址。
                <br />
                在“验证码”那里，你可以选择是否想要使用验证码来阻止垃圾信息。<br />
                简介文字仅在类别列表页面显示。<br />
                详细说明将显示在表单页面。<br />
                Email 提示文字是给你自己的提示消息。只会出现在反馈回来的邮件中。
                <br />
                <br />
                <strong>第四步:</strong><br />
                链接.<br />
                在后台的“表单概览”页面上，你可以将表单或者类别链接到菜单。你也可以将“全部表单”或者“未分类表单”链接到菜单。</p>
            </td>
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>

