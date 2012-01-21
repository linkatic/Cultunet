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
	//Japanese Translation by Masato Sato<webmaster@joomlaway.net> URL:http://www.joomlaway.net/
	/**  ENGLISH VERSION. NEEDS TO BE TRANSLATED */

defined( '_VALID_MOS' ) or die( 'この場所への直接アクセスは許可されていません。' ); 
?>


<table width="944" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="400" height="309" align="left" valign="top"><img src="components/com_mad4joomla/images/mad4media-3d.png" width="400" height="309"></td>
        <td align="left" valign="top"><h3>Mad4Joomla Mailforms V <?PHP echo M4J_VERSION_NO; ?></h3>
          This component were build by  Dipl. Informatiker (similar to MSc) Fahrettin Kutyol for Mad4Media.<br>
          Mad4Mediaはユーザ中心のデザインでソフトウェアを開発します。私たちの製品およびプロジェクトは、人間工学(ユーザビリティ)を最大限に発揮するためユーザに利用しやすいように設計されています。その他JavaやPHPでのコーディング、JoomlaやosCommerce向けのエクステンション開発を顧客に提供します。私たちのサービスに興味がある場合、ホームページ<a href="http://www.mad4media.de" target="_blank">Mad4Media</a>から連絡することができます。: <br>
          <br>
          <strong>ライセンスと保証</strong><br>
          Mad4Joomla MailformsはGNU GPLライセンスで公開されています。機能性や完全性において保証はありません。Mad4Mediaは、このコンポーネントによって発生した損害の責任を負いません。<br>
          <br>
          <strong>実装済みオープンソースコンポーネント:</strong><br>
          <a href="http://www.dynarch.com/projects/calendar/" target="_blank">jsCalendar</a> - LGPL<br>
          アイコン:<a href="http://www.dryicons.com" target="_blank">dryicons.com</a> - Creative Commons Attribution 3.0 License<br>
          <a href="http://www.dhtmlgoodies.com" target="_blank">Balloontip</a> - LGPL <br>
          <br>
          <br></td>
      </tr>
    </table>
	
	    <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="top"><h3>Mad4Joomlaメールフォームについて</h3>
		      <p align="left">Mad4Joomlaはメールフォームを作成するための、簡単に使えるコンポーネントです。<br /> 
	          この製品の優位な点は、より良いユーザビリティ、カテゴリでの分類、テンプレートで動作すること、フォームフィールドへのヘルプテキスト、送信先メールアドレスへの特別なルーティング技術、フォームページへのコンテンツ統合、そして特別な新しいキャプチャ技術です。これにより、無限かつ複雑な連絡システムを構築する事が可能です。例: 求人、予約など</p>
		      <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" height="100%" align="left" valign="top"><h3>Mad4Joomlaホーム</h3>
                  <p>今後はmad4mediaのホームで解説情報を入手します。
                    <br />
                    私たちは翻訳を高く評価します。次のアドレスから分割された言語ファイルをダウンロードできます。あなたの翻訳を私たちに提供して下さい。 
                    言語ファイルをコンポーネントパッケージに添付して公開します。<br />
                    プロジェクトページへは次からアクセスできます: <a href="http://www.mad4media.de/mad4joomla-mailforms.html" target="_blank">www.mad4media.de/mad4joomla-mailforms.html
                  </a></p>
                  <p>翻訳者は自身のホームページへのバックリンク(下記のような)を手に入れます。<br />
                  <h3>翻訳</h3>
                    English, German by <a href="www.mad4media.de" target="_blank">mad4media</a><br />
                    Frontend Portuguese by 
                    <a href="mailto:tecnicoisaias@yahoo.com.br">Isaias Santana
                    </a><br />  
                    <br />
                  </p>
                </td>
              </tr>
              </table>			<h3>&nbsp;</h3></td>
            <td width="52%" align="left" valign="top"><h3>スタートガイド <br />
            </h3>
              <p><strong>ステップ1:</strong><br />
                カテゴリは必要ですか？<br />
                例えば複数の求人を募集する場合、「求人」という名前のカテゴリを追加することをお勧めします。カテゴリを作成する事で、カテゴリページの先頭に表示するコンテンツを追加できます。フォームにメールアドレスが設定されていない場合、カテゴリのメールアドレスが代わりに使用されます。カテゴリにメールアドレスを設定しない場合、メインメールアドレス(設定ページ)が代わりに使用されます。
                <br />
                <br />
                <strong>ステップ2:</strong><br />
              1つ以上のテンプレートを適用します<br />
              基本データエリアに短い説明を入力する事ができます。これはテンプレートを識別するためのものです。テンプレートはフォームテーブルの列幅および列高さを適用するために重要です。次のステップでフォームフィールドを適用する必要があります。フロントエンドでマウスオーバー時に表示されるヘルプテキストをフィールドに追加できます。<br />
              <br />
                <strong>ステップ3</strong><br />
                フォームを適用します。<br />
                タイトルを入力し、カテゴリを割り当てて下さい。カテゴリを割り当てない場合「カテゴリなし」を選択して下さい。<br />
                次にテンプレートを割り当てる必要があります。ターゲットのメールアドレスを設定しない場合、カテゴリのターゲットアドレスにメールが送信されます。カテゴリにターゲットメールアドレスがない場合、代わりにメインメールアドレスが使用されます。
                <br />
                「キャプチャ」で、ボットのスパム投稿を防ぐためにセキュリティチェックを使用するかどうか選択できます。<br />
                イントロテキストはカテゴリ一覧にのみ表示されます。<br />
                メインテキストはフォームページにのみ表示されます<br />
                メールイントロテキストはあなた自身のヒントです。これは返信メールにのみ表示されます。
                <br />
                <br />
                <strong>ステップ4</strong><br />
                リンクします。<br />
                バックエンドのフォーム概要ページで、フォームやカテゴリをメニューにリンクできます。また「全てのフォーム」や「カテゴリなし」をメニューにリンクする事も可能です。</p>
            </td>
          </tr>
      </table>      
      <p>&nbsp;</p></td>
  </tr>
</table>

