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
          本元件由  Dipl. Informatiker (similar to MSc) Fahrettin Kutyol 為 Mad4Media 開發.<br>
          Mad4Media 總是以用戶為中心來開發軟件。我們的產品和項目總是面向用戶而設計，以期獲得最大的工效學成功（易用性）。除了用 Java 和 php 編程，我們還為客戶提供定制的 Joomla 或者 osCommerce 擴展開發。如果您對我們的服務有興趣，請通過我們網站與我們聯系: <a href="http://www.mad4media.de" target="_blank">Mad4Media</a><br>
          <br>
          <strong>協議及承諾</strong><br>
          Mad4Joomla Mailforms 元件遵照 GNU GPL 協議發行. 我們不承諾任何質量保證或代碼完整性。Mad4Media 也不會為因使用本元件而造成的損失負責。<br>
          <br>
          <strong>在本元件中使用了下列開源產品:</strong><br>
          <a href="http://www.dynarch.com/projects/calendar/" target="_blank">jsCalendar</a> - LGPL<br>
          圖示來自 <a href="http://www.dryicons.com" target="_blank">dryicons.com</a> - Creative Commons Attribution 3.0 License<br>
          <a href="http://www.dhtmlgoodies.com" target="_blank">Balloontip</a> - LGPL <br>
          <br>
          <br></td>
      </tr>
    </table>

	    <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="top"><h3>關于 Mad4Joomla Mailforms</h3>
		      <p align="left">Mad4Joomla 是一個簡單的表單設計元件，功能是幫助您設計 email 表單.<br />
	          本元件的設計宗旨是：更好的易用性，可按類別分類，借助模板更改外觀，表單字段提供提示資訊，對目標郵件地址進行特殊追蹤，將表單頁面與文章整合，全新的驗證碼引擎。因此，也可以用它設計出復雜的、龐大的聯系系統。示范: Jobs, Reservation etc. </p>
		      <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" height="100%" align="left" valign="top"><h3>Mad4Joomla 官方網站</h3>
                  <p>在 Mad4Joomla 官方網站你可以獲取詳細的資訊。
                    <br />
                    我們對任何語言的翻譯都表示感謝. 你可以從下面地址下載單獨的語言文件包，（在此基礎上翻譯）完后將你的成果發送給我們。我們將把它添加到安裝包中，公開發行。<br />
                    下載地址：<a href="http://www.mad4media.de/mad4joomla-mailforms.html" target="_blank">www.mad4media.de/mad4joomla-mailforms.html
                  </a></p>
                  <p>我們將在此設定指向翻譯人員網站的連結（就像下面這個）. <br />
                  <h3>翻譯人員</h3>
                    English, German by <a href="www.mad4media.de" target="_blank">mad4media</a><br />
                    Simplified Chinese and Traditional Chinese by <a href="http://www.joomlagate.com" target="_blank">baijianpeng</a><br />
                  <br />
                    <br />
                    <br />
                  </p>
                </td>
              </tr>
              </table>			<h3>&nbsp;</h3></td>
            <td width="52%" align="left" valign="top"><h3>簡易操作說明 <br />
            </h3>
              <p><strong>第一步:</strong><br />
                你是否需要創建類別? <br />
                例如：你可能想要發布多個工作機會，那么我們建議創建一個名為“jobs”的類別。 當創建類別時，你可以添加一些文字，這些文字將顯示在類別頁面的頂部。如果某個表單沒有指定目標 email 地址, 就會使用所在類別的郵件地址。如果你的類別也沒有指定目標 email 地址，則會使用主郵件地址（在主要設定中）.
                <br />
                <br />
                <strong>第二步:</strong><br />
              創建一個或多個模板.<br />
              你可以在基本資料區域輸入一些簡介文字。這是為了區分各個模板。你一定要指定表單表格的列寬度。在下一步，你就需要創建表單字段了。你可以給每一個字段輸入提示資訊文字，當前臺訪客用滑鼠指向幫助圖示時，就會看到氣泡式的提示消息。<br />
              <br />
                <strong>第三步:</strong><br />
                創建表單.<br />
                輸入表單名稱，指定所屬類別。如果你不想指定類別，就選擇“未分類”。<br />
                接下來，你必須為此表單選擇模板。如果你不指定目標 email 地址，前臺提交后的內容將發送到此表單所屬類別的目標郵件地址。如果該類別也沒有目標郵件地址，則會發送給主郵件地址。
                <br />
                在“驗證碼”那里，你可以選擇是否想要使用驗證碼來阻止垃圾資訊。<br />
                簡介文字僅在類別清單頁面顯示。<br />
                詳細說明將顯示在表單頁面。<br />
                Email 提示文字是給你自己的提示消息。只會出現在反饋回來的郵件中。
                <br />
                <br />
                <strong>第四步:</strong><br />
                連結.<br />
                在后臺的“表單概覽”頁面上，你可以將表單或者類別連結到選單。你也可以將“全部表單”或者“未分類表單”連結到選單。</p>
            </td>
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>

