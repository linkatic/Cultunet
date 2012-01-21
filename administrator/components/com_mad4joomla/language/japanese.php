<?php
	/**
	* @version $Id: Mooj 10041 2008-03-18 21:48:13Z fahrettinkutyol $
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


	defined( '_JEXEC' ) or die( 'この場所への直接アクセスは許可されていません。' );
	
	$m4j_lang_elements[1]= 'チェックボックス';
	$m4j_lang_elements[2]= 'はい/いいえ選択';
	$m4j_lang_elements[10]= '日付';
	$m4j_lang_elements[20]= 'テキストフィールド';
	$m4j_lang_elements[21]= 'テキストエリア';
	$m4j_lang_elements[30]= 'メニュー(1つ選択)';
	$m4j_lang_elements[31]= 'セレクトメニュー(1つ選択)';
	$m4j_lang_elements[32]= 'ラジオボタン(1つ選択)';
	$m4j_lang_elements[33]= 'チェックボックスグループ(複数選択)';
	$m4j_lang_elements[34]= 'リスト(複数選択)';
	
	
	define('M4J_LANG_FORMS','フォーム');
	define('M4J_LANG_TEMPLATES','テンプレート');
	define('M4J_LANG_CATEGORY','カテゴリ');
	define('M4J_LANG_CONFIG','設定');
	define('M4J_LANG_HELP','情報 & ヘルプ');
	define('M4J_LANG_CANCEL','キャンセル');
	define('M4J_LANG_PROCEED','進む');
	define('M4J_LANG_SAVE','保存');
	define('M4J_LANG_NEW_FORM','新規フォーム');
	define('M4J_LANG_NEW_TEMPLATE','新規テンプレート');
	define('M4J_LANG_ADD','追加');
	define('M4J_LANG_EDIT_NAME','このテンプレートの名前と説明を編集');
	define('M4J_LANG_NEW_TEMPLATE_LONG','新規テンプレート');
	define('M4J_LANG_TEMPLATE_NAME','このテンプレートの名前');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','このテンプレートの名前を編集');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','短い説明 (内部で使用。空欄にもできます。)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','短い説明を編集');
	define('M4J_LANG_DELETE','削除');
	define('M4J_LANG_DELETE_CONFIRM','このアイテムを本当に削除してよろしいですか？');
	define('M4J_LANG_NEW_CATEGORY','新規カテゴリ');
	define('M4J_LANG_NAME','名前');
	define('M4J_LANG_SHORTDESCRIPTION','短い説明');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','アイテム');
	define('M4J_LANG_EDIT','編集');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','アイテム -> 編集');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','このテンプレートの名前を入力して下さい！');
	define('M4J_LANG_AT_LEAST_ONE','このアイテムは削除できませんでした！<br/>少なくとも1つの要素が必要です。');	

	
		define('M4J_LANG_EDIT_ELEMENT','テンプレートの要素を編集:: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','カテゴリ名を入力して下さい');
	define('M4J_LANG_NONE_LEGAL_EMAIL','正しいメールアドレスを入力するか、空欄にしてください。<br/>');
	define('M4J_LANG_EMAIL','メールアドレス');
	define('M4J_LANG_POSITION','並び順');
	define('M4J_LANG_ACTIVE','有効');
	define('M4J_LANG_UP','上へ');
	define('M4J_LANG_DOWN','下へ');
	define('M4J_LANG_EDIT_CATEGORY','カテゴリ編集');
	define('M4J_LANG_TEMPLATE_ELEMENTS','テンプレートの要素');
	define('M4J_LANG_NEW_ELEMENT_LONG','テンプレートに新しい要素を挿入: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','質問を入力して下さい。');
	define('M4J_LANG_REQUIRED','必須');
	define('M4J_LANG_QUESTION','質問');
	define('M4J_LANG_TYPE','種類');
	define('M4J_LANG_YES','はい');		
	define('M4J_LANG_NO','いいえ');	
	define('M4J_LANG_ALL_FORMS','全てのフォーム');
	define('M4J_LANG_NO_CATEGORYS','カテゴリなし');
	define('M4J_LANG_FORMS_OF_CATEGORY','カテゴリのフォーム: ');
	define('M4J_LANG_PREVIEW','プレビュー');
	define('M4J_LANG_DO_COPY','コピー');		
	define('M4J_LANG_COPY','コピー');
	define('M4J_LANG_VERTICAL','垂直');
	define('M4J_LANG_HORIZONTAL','水平');
	define('M4J_LANG_EXAMPLE','例');
	define('M4J_LANG_CHECKBOX','ボタン');	
	define('M4J_LANG_DATE','日付');
	define('M4J_LANG_TEXTFIELD','テキストフィールド');
	define('M4J_LANG_OPTIONS','指定選択');
	define('M4J_LANG_CHECKBOX_DESC','シンプルな「はい」「いいえ」の質問です。');
	define('M4J_LANG_DATE_DESC','ユーザは日付を入力する必要があります。');
	define('M4J_LANG_TEXTFIELD_DESC','ユーザは各テキストを入力する必要があります。');
	define('M4J_LANG_OPTIONS_DESC','ユーザは指定された項目から1つ以上の回答を選択します。');
	define('M4J_LANG_CLOSE_PREVIEW','プレビューを閉じる');
	define('M4J_LANG_Q_WIDTH','質問カラムの幅(左側)');
	define('M4J_LANG_A_WIDTH','回答カラムの幅(右側)');
	define('M4J_LANG_OVERVIEW','概観');
	define('M4J_LANG_UPDATE_PROCEED','& 進む');
	define('M4J_LANG_NEW_ITEM','新規アイテム');
	define('M4J_LANG_EDIT_ITEM','アイテム編集');
	define('M4J_LANG_CATEGORY_NAME','カテゴリ名');
	define('M4J_LANG_EMAIL_ADRESS','メールアドレス');
	define('M4J_LANG_ADD_NEW_ITEM','新規アイテムを追加');
	define('M4J_LANG_YOUR_QUESTION','あなたの質問');
	define('M4J_LANG_REQUIRED_LONG','必須にしますか？');
	define('M4J_LANG_HELP_TEXT','ヘルプテキストです。質問のヒントをユーザに表示します。(必須ではありません)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','ボタンのタイプ');
	define('M4J_LANG_ITEM_CHECKBOX','チェックボックス');
	define('M4J_LANG_YES_NO_MENU','はい/いいえメニュー');
	define('M4J_LANG_YES_ON','はい/オン');
	define('M4J_LANG_NO_OFF','いいえ/オフ');
	define('M4J_LANG_INIT_VALUE','初期値:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','テキストフィールドのタイプ:');
	define('M4J_LANG_ITEM_TEXTFIELD','テキストフィールド');
	define('M4J_LANG_ITEM_TEXTAREA','テキストエリア');
	define('M4J_LANG_MAXCHARS_LONG','最大文字数:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','表示設定:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>ピクセルでの幅</b> <br/>(パーセンテージ指定時は\'%\'を追加して下さい。空欄 = 自動調整)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>表示する行数:</b><br/> (テキストエリアのみ)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>メニュー</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>ラジオボタン</b>');
	define('M4J_LANG_LIST_SINGLE','<b>リスト</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1つ選択)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>チェックボックスグループ</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>リスト</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(CTRLを押しながら左クリックで複数選択)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','1つ選択 (項目を1つだけ選択できます):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','複数選択 (複数のアイテムを選択できます):');
	define('M4J_LANG_TYPE_OF_OPTIONS','選択の種類:');
	define('M4J_LANG_ROWS_LIST','<b>表示する行数:</b><br/> (リストのみ)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>配置: </b> <br/>(ラジオボタンおよびチェックボックスグループのみ)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>回答を指定して下さい。<br/>空欄のフィールドは無視されます。</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','イントロテキストです。カテゴリ表示にのみ表示されます。');
	define('M4J_LANG_TITLE','タイトル');
	define('M4J_LANG_ERROR_NO_TITLE','タイトルを入力して下さい。');
	define('M4J_LANG_USE_HELP','フロントエンドのバルーンチップのヘルプテキスト');
	define('M4J_LANG_TITLE_FORM','フォームタイトル');
	define('M4J_LANG_INTROTEXT','イントロテキスト');
	define('M4J_LANG_MAINTEXT','本文');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','メールのイントロテキストです。(メールに表示されます。)');
	define('M4J_LANG_TEMPLATE','テンプレート');
	define('M4J_LANG_LINK_TO_MENU','メニューへリンク');
	define('M4J_LANG_LINK_CAT_TO_MENU','現在のカテゴリをメニューへリンク');
	define('M4J_LANG_LINK_TO_CAT','リンクするカテゴリ: ');
	define('M4J_LANG_LINK_TO_FORM','フォームへリンク: ');
	define('M4J_LANG_LINK_TO_NO_CAT','カテゴリなしで全てのフォームを表示するリンク');
	define('M4J_LANG_LINK_TO_ALL_CAT','「全てのカテゴリ」を表示するリンク');
	define('M4J_LANG_CHOOSE_MENU','リンクするメニューを選択してください: ');
	define('M4J_LANG_MENU','メニュー: ');
	define('M4J_LANG_NO_LINK_NAME','リンク名を入力して下さい。');
	define('M4J_LANG_PUBLISHED','公開:');
	define('M4J_LANG_PARENT_LINK','親リンク');
	define('M4J_LANG_LINK_NAME','リンク名');
	define('M4J_LANG_ACCESS_LEVEL','アクセスレベル:');
	define('M4J_LANG_EDIT_MAIN_DATA','基本データを編集');
	define('M4J_LANG_LEGEND','凡例');
	define('M4J_LANG_LINK','リンク');
	define('M4J_LANG_IS_VISIBLE',' は公開済み');
	define('M4J_LANG_IS_HIDDEN',' は非公開');
	define('M4J_LANG_FORM','フォーム');
	define('M4J_LANG_ITEM','アイテム');
	define('M4J_LANG_IS_REQUIRED','必須');
	define('M4J_LANG_IS_NOT_REQUIRED','任意');
	define('M4J_LANG_ASSIGN_ORDER','並び順');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* 「全てのフォーム」で並び順は変更できません!<br/>');
	define('M4J_LANG_EDIT_FORM','フォーム編集');
	define('M4J_LANG_CAPTCHA','キャプチャ');
	define('M4J_LANG_HOVER_ACTIVE_ON','公開時にクリック=非公開へ');
	define('M4J_LANG_HOVER_ACTIVE_OFF','非公開時にクリック=公開へ');
	define('M4J_LANG_HOVER_REQUIRED_ON','必須時にクリック=任意へ');
	define('M4J_LANG_HOVER_REQUIRED_OFF','任意時にクリック= 必須へ');
	define('M4J_LANG_DESCRIPTION','説明');
	define('M4J_LANG_AREA','エリア');
	define('M4J_LANG_ADJUSTMENT','設定');
	define('M4J_LANG_VALUE','設定');
	define('M4J_LANG_MAIN_CONFIG','メイン設定:');
	define('M4J_LANG_MAIN_CONFIG_DESC','ここで全てのメイン設定が行えます。全てのメイン設定(CSS含む)を初期値にリセットしたい場合、「リセット」をクリックして下さい。');
	define('M4J_LANG_CSS_CONFIG','CSS設定:');
	define('M4J_LANG_CSS_CONFIG_DESC','これらの設定はフロントエンドの視覚的な表現に必要です。外部CSS(自身のCSS）を含んだ事がない場合、これらの値を変更しないで下さい！');
	define('M4J_LANG_RESET','リセット');
			
	define('M4J_LANG_EMAIL_ROOT', 'メインメールアドレス: ' );
	define('M4J_LANG_MAX_OPTIONS', '指定選択の<br/> 最大回答数: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'プレビュー幅: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'プレビュー高さ: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'キャプチャ間隔 (分): ' );
	define('M4J_LANG_HELP_ICON', 'ヘルプアイコン: ' );
	define('M4J_LANG_HTML_MAIL', 'HTMLメール: ' );
	define('M4J_LANG_SHOW_LEGEND', '凡例を表示: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'カテゴリ、フォームのどちらにもメールアドレスが割り当てられていない場合に、メインメールアドレスが使用されます。' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'この値は「指定選択」項目の最大回答数を制限します。この値は数値で入力してください。' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'テンプレートプレビューの幅です。テンプレートの基本データにプレビュー幅を割り当てていない場合にのみ、この値が使用されます。' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'テンプレートプレビューの高さです。 ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'フロントエンドに属します。この値はキャプチャによる正当性検査の最大持続時間を割り当てます。持続時間が経過すると、古いコードが正しくても新しいキャプチャコードを入力する必要があります。' );
	define('M4J_LANG_HELP_ICON_DESC', 'ヘルプアイコンの色を設定して下さい。' );
	define('M4J_LANG_HTML_MAIL_DESC', 'HTMLメールを受信する場合は「はい」を選択して下さい。' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'バックエンドで凡例を表示する場合は「はい」を選択して下さい。' );
	
	define('M4J_LANG_CLASS_HEADING', 'メインの見出し:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', '見出しテキスト' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'リスト- 囲み ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'リスト- 見出し' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'リスト- イントロテキスト ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'フォーム- 囲み ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'フォーム- テーブル ' );
	define('M4J_LANG_CLASS_ERROR', 'エラーテキスト' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', '送信ボタン囲み' );
	define('M4J_LANG_CLASS_SUBMIT', '送信ボタン ' );
	define('M4J_LANG_CLASS_REQUIRED', '必須項目 * css ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIVタグ</strong> - サイトの見出し' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIVタグ</strong> - 見出し後のコンテンツ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIVタグ</strong> - リストアイテムの囲み' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIVタグ</strong> - リストアイテムの見出し' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIVタグ</strong> - リストアイテムのイントロテキスト' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIVタグ</strong> - フォーム領域の囲み' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLEタグ</strong> - 全てのフォームアイテムが表示されるテーブル' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPANタグ</strong> - エラーメッセージのCSSクラス' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIVタグ</strong> - 送信ボタンの囲み' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUTタグ</strong> - 送信ボタンのCSSクラス' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPANタグ</strong> - 必須項目である事を示す「<b>*</b>」のCSSクラス' );
	
	define('M4J_LANG_INFO_HELP','情報とヘルプ');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'キャプチャ手法: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','通常');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','設定は正常にリセットされました。');
		define('M4J_LANG_CONFIG_SAVED','設定は正常に保存されました。');
		define('M4J_LANG_CAPTCHA_DESC', ' 標準のCSSキャプチャは、いくつかのサーバ、テンプレートで問題があるかもしれません。このような場合、標準CSSキャプチャと通常のキャプチャの中から選択する代案があります。通常のキャプチャを選択しても問題が解決しない場合は「スペシャル」を選択してください' );
		define('M4J_LANG_SPECIAL','スペシャル');
	
	
	define('M4J_LANG_MAIL_ISO','メール文字コード');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (西ヨーロッパ), iso-8859-4 (バルト), iso-8859-5 (キリル文字), iso-8859-6 (アラビア語), iso-8859-7 (ギリシャ語), iso-8859-8 (ヘブライ語),iso-8859-9 (トルコ語), iso-8859-10 (北欧),iso-8859-11 (タイ)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= '添付';	
	define('M4J_LANG_ATTACHMENT','ファイル添付');
	define('M4J_LANG_ATTACHMENT_DESC','ユーザはフォームからファイルを送信できます。');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','ファイル転送フィールドのパラメータを入力してください:');
	define('M4J_LANG_ALLOWED_ENDINGS','許可するファイル拡張子');
	define('M4J_LANG_MAXSIZE','最大ファイルサイズ');
	define('M4J_LANG_BYTE','バイト');
	define('M4J_LANG_KILOBYTE','キロバイト');
	define('M4J_LANG_MEGABYTE','メガバイト');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','全てのファイル拡張子をドットなしの<b>カンマ</b>区切りで入力して下さい。フィールドを空欄にした場合、全てのファイル拡張子が許可され、どんなサイズでも認められます。この作業を簡単にするために、自動で表示された以下の拡張子の中から選択する事ができます。');
	define('M4J_LANG_IMAGES','画像');
	define('M4J_LANG_DOCS','ドキュメント');
	define('M4J_LANG_AUDIO','オーディオ');
	define('M4J_LANG_VIDEO','ビデオ');										   
    define('M4J_LANG_DATA','データ');
	define('M4J_LANG_COMPRESSED','圧縮');
	define('M4J_LANG_OTHERS','その他');
	define('M4J_LANG_ALL','全て');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','差出人名');
	define('M4J_LANG_FROM_EMAIL','フォームメールアドレス');
	define('M4J_LANG_FROM_NAME_DESC','送信するメールの差出人名を入力してください<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','送信するメールの差出人メールアドレスを入力して下さい。<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' 警告! このテンプレートに含まれる全てのフォームも削除されます!');
	
	
	// New to Proforms 1.0
	
	define('M4J_LANG_STORAGES','Database records of the form: ');
	define('M4J_LANG_READ_STORAGES','Database records');
	define('M4J_LANG_EXPORT','Export');
	define('M4J_LANG_CSV_EXPORT','CSV Export');
	define('M4J_LANG_WORKAREA','Workarea');
	define('M4J_LANG_WORKAREA_DESC','Width in pixel of the admin working window');
	define('M4J_LANG_STORAGE_WIDTH','Width of a database item');
	define('M4J_LANG_STORAGE_WIDTH_DESC','Width in pixel of a database item which will be listed in a database record.<br> Don\'t add px or % !');
	define('M4J_LANG_RECEIVED','Received');
	define('M4J_LANG_PROCESS','Process');
	define('M4J_LANG_DATABASE','Database');
	define('M4J_LANG_USERMAIL','Unique email address');
	define('M4J_LANG_USERMAIL_DESC','Hereby you can assign the specific field which represents the unique email address of the user.You can not use the confirmation (copy mail) function without assigning an unique email address. There can always be only one unique email address for each form template. Activating this will delete the current unique email address.');
	define('M4J_LANG_USERMAIL_TOOLTIP','This field is the unique email address. The unique email address is always set to `required`!');
	define('M4J_LANG_MATH','Mathematical');
	define('M4J_LANG_RE_CAPTCHA','reCAPTCHA');
	define('M4J_LANG_ITEM_PASSWORD','Password');
	$m4j_lang_elements[22]= 'Password';
	define('M4J_LANG_MAX_UPLOAD_ALLOWED','Your server allows a maximal upload size of ');
	define('M4J_LANG_CSS_EDIT', 'Edit CSS');
	define('M4J_LANG_NO_FRONT_STYLESHEET','The frontend stylesheet file doesn\'t exist! ');
	define('M4J_LANG_HTML','HTML');
	define('M4J_LANG_HTML_DESC','Allows you to display custom HTML code between the form elements.');
	$m4j_lang_elements[50]= 'HTML';
	define('M4J_LANG_EXTRA_HTML',' - EXTRA HTML - ');
	define('M4J_LANG_RESET_DESC','Resetting the configuration to the default setting.');
	define('M4J_LANG_SECURITY','Captcha &amp; Security');
	define('M4J_LANG_RECAPTCHA_THEME','reCaptcha Theme');
	define('M4J_LANG_RECAPTCHA_THEME_DESC','If you are using reCaptcha, you can change the theme.');
	define('M4J_LANG_SUBMISSION_TIME','Sending speed (in ms)');
	define('M4J_LANG_SUBMISSION_TIME_DESC','This value is in milliseconds, determines the acceptable time between the appearance of a form and its dispatch. If a dispatch is quicker than the specified context, it will be declared and rejected as spam.');
	define('M4J_LANG_FORM_TITLE','Show title');
	define('M4J_LANG_FORM_TITLE_DESC','Determines whether the title of a form is displayed. Applies only to the form display and not for the listing in one category.');
	define('M4J_LANG_SHOW_NO_CATEGORY','Show "Without Category"');
	define('M4J_LANG_SHOW_NO_CATEGORY_DESC','Here you can determine the appearance of the pseudo-category "without category". Depending on the setting it will be displayed in the main category listing or not.');
	define('M4J_LANG_FORCE_CALENDAR','Force English calendar');
	define('M4J_LANG_FORCE_CALENDAR_DESC','Under some front-end languages the calendar may not function properly. You can force the use of an English-calendar.');
	define('M4J_LANG_LINK_THIS_CAT_ALL','Link the listing of all categories to a menu.');
	define('M4J_LANG_LINK_THIS_NO_CAT','Link all forms as belonging to a category list to a menu.');
	define('M4J_LANG_LINK_THIS_CAT','Link all forms in the category \'%s\'as a list to a menu.');
	define('M4J_LANG_LINK_THIS_FORM','Link this form to a menu.');
	define('M4J_LANG_LINK_ADVICE','You can link categories and forms only with the given buttons [%s] to a menu!');
	define('M4J_LANG_HELP_TEXT_SHORT','Helptext');
	define('M4J_LANG_ROWS','Rows');
	define('M4J_LANG_WIDTH','Width');
	define('M4J_LANG_ALIGNMENT','Alignment');
	define('M4J_LANG_SHOW_USER_INFO','Show user info');
	define('M4J_LANG_SHOW_USER_INFO_DESC','Displays a list of the collected user data in emails. For example: Joomla Username, Joomla User Email, browser, OS, etc. This data will not be displayed in confirmation emails for the sender of the form.');
	define('M4J_LANG_FRONTEND','Frontend');
	define('M4J_LANG_ADMIN','Admin');
	define('M4J_LANG_DISPLAY','Display');
	define('M4J_LANG_FORCE_ADMIN_LANG','Force admin language');
	define('M4J_LANG_FORCE_ADMIN_LANG_DESC','In normal Proform recognizes the Admin language automatically. Here you can force a language.');
	define('M4J_LANG_USE_JS_VALIDATION','Javascript validation');
	define('M4J_LANG_USE_JS_VALIDATION_DESC','Here you can switch the javascript form validation. If this is off, the fields will be evaluated after submitting.');
	define('M4J_LANG_PLEASE_SELECT','Please Select');
	define('M4J_LANG_LAYOUT','Layout');
	define('M4J_LANG_DESC_LAYOUT01','One column');
	define('M4J_LANG_DESC_LAYOUT02','Two columns');
	define('M4J_LANG_DESC_LAYOUT03','Three columns');
	define('M4J_LANG_DESC_LAYOUT04','Head with two columns and footer with one column');
	define('M4J_LANG_DESC_LAYOUT05','Head with one column and footer with two columns');
	define('M4J_LANG_USE_FIELDSET','Use fieldset:');
	define('M4J_LANG_LEGEND_NAME','Legend:');
	define('M4J_LANG_LEFT_COL','Left column:');
	define('M4J_LANG_RIGHT_COL','Right column:');
	define('M4J_LANG_FOR_POSITION',' for position %s');
	define('M4J_LANG_LAYOUT_POSITION','Layout position');
	define('M4J_LANG_PAYPAL','PayPal');
	define('M4J_LANG_EMAIL_TEXT','Email text');
	define('M4J_LANG_CODE','Code');
	define('M4J_LANG_NEVER','Never');
	define('M4J_LANG_EVER','Ever');
	define('M4J_LANG_ASK','Ask');
	define('M4J_LANG_AFTER_SENDING','After sending');
	define('M4J_LANG_CONFIRMATION_MAIL','Confirmation Mail');
	define('M4J_LANG_TEXT_FOR_CONFIRMATION','Email text only for confirmation mail?');
	define('M4J_LANG_SUBJECT','Subject');
	define('M4J_LANG_ADD_TEMPLATE','Add form template');
	define('M4J_LANG_INCLUDED_TEMPLATES','Included form template(s)');
	define('M4J_LANG_ADVICE_USERMAIL_ERROR',"A form can only ever have one unique email address. You already have assigned a form template with unique email address to this form.");
	define('M4J_LANG_STANDARD_TEXT','Standard text');
	define('M4J_LANG_REDIRECT','Redirection');
	define('M4J_LANG_CUSTOM_TEXT','Custom text');
	define('M4J_LANG_ERROR_NO_FORMS','You can only create a form if you have at least created one form template. You have not created yet a form template. Do you wish to create a new form template?');
	define('M4J_LANG_USE_PAYPAL','Use PayPal');
	define('M4J_LANG_USE_PAYPAL_SANDBOX','Use PayPal Sandbox');
	define('M4J_LANG_HEIGHT','Height');
	define('M4J_LANG_CLASS_RESET','Reset Button');
	define('M4J_LANG_CLASS_RESET_DESC','<b>INPUT-Tag</b> - CSS class for the reset button.');
	define('M4J_LANG_PAYPAL_PARAMETERS','Paypal configuration');
	define('M4J_LANG_PAYPAL_ID','Your PayPal ID (email)');
	define('M4J_LANG_PAYPAL_PRODUCT_NAME','Product name');
	define('M4J_LANG_PAYPAL_QTY','Quantity');
	define('M4J_LANG_PAYPAL_NET_AMOUNT','Net amount (unit price)');
	define('M4J_LANG_PAYPAL_CURRENCY_CODE','Currency Code');
	define('M4J_LANG_PAYPAL_ADD_TAX','Plus TAX (Overall, not a percentage!) ');
	define('M4J_LANG_PAYPAL_RETURN_URL','Return address after a successful transaction (URL with http)');
	define('M4J_LANG_PAYPAL_CANCEL_RETURN_URL','Return address when the transaction is aborted (URL with http) ');
	define('M4J_LANG_SERVICE','Service');
	define('M4J_LANG_SERVICE_KEY','Service Key');
	define('M4J_LANG_EDIT_KEY','Edit / Renew Key');
	define('M4J_LANG_CONNECT','Connect');
	define('M4J_LANG_NONE','None');
	define('M4J_LANG_ALPHABETICAL','Alphabetical');
	define('M4J_LANG_ALPHANUMERIC','Alphanumeric');
	define('M4J_LANG_NUMERIC','Numeric');
	define('M4J_LANG_INTEGER','Integer');
	define('M4J_LANG_FIELD_VALIDATION','Validation');
	define('M4J_LANG_SEARCH','Search');
	define('M4J_LANG_ANY','-ANY-');
	define('M4J_LANG_JOBS_EMAIL_INFO','If you don\'t enter an e-mail address here the address of the corresponding category will be used. <br /> If there is no address appended the global address will be used (see configuration).');
	define('M4J_LANG_JOBS_INTROTEXT_INFO','The intro text is the text which will be displayed by a (category) forms list. This does not appear on the form itself.');
	define('M4J_LANG_JOBS_MAINTEXT_INFO','The main text appears at the top of the form.');
	define('M4J_LANG_JOBS_AFTERSENDING_INFO','Here you can determine what will be displayed after submitting the form data.');
	define('M4J_LANG_JOBS_PAYPAL_INFO','After sending you can redirect the user directly to Paypal. Please enter the amounts with a point instead of a comma: 24.50 instead of 24,50! <br /> If you use PayPal, the action you\'ve choosen in "After sending" will passed over !');
	define('M4J_LANG_JOBS_CODE_INFO','You also can enter custom code (HTML, JS <b> but no PHP </b>) at the end of the form or at the after sending page:<br /> e.g. Google Analytics or Conversion. The "after-sending-code" will not be  included if you use an after-sending redirection or the PayPal function.');
	define('M4J_LANG_ERROR_COLOR','Error color');
	define('M4J_LANG_ERROR_COLOR_DESC','If the javascript form validation detects an error ther border of a cell will displayed in a special color. Here you can determine the color (Hexadecimal without #).');
	define('M4J_LANG_CONFIG_DISPLAY_INFO','Here you can change values which are influencing the representation of the front or the back end.');
	define('M4J_LANG_CONFIG_CAPTCHA_INFO','Here you can determine the technology of the security check (captcha) and other security settings.');
	define('M4J_LANG_CONFIG_RESET_INFO','The style sheet file will not be reset, only the CSS class name of the CSS settings!');
	define('M4J_LANG_SERVICE_DESC1',
	'
	If you have a service key you will get access to the Proforms Service Helpdesk here.<br/> 
	To do so, enter the key and save it. Afterwards you need to connect through the "Connect" button with the Service Help Desk Server.<br/>  
	<br/> 
	You can reach the service desk only from the admin area of Proforms.<br/>  
	Direct access is not allowed.<br/>  
	<br/> 
	Each service key is temporary and can\'t be used by the end of the service period. The service key is only valid for one domain / Joomla installation. At the first visit of the helpdesk, you will be asked if you want to register the key on the current Domain / Joomla installation. When you click OK, you get access to the helpdesk. Then you can reach the help desk with this key only from the admin area of the registered domain / Joomla installation.<br/>  
	<br/><span style="color:red"> 
	If this installation (domain) is behind a firewall or is generally not publicly accessible (e.g. running on a local server), we can\'t offer the service for technical reasons (see Technical Requirements and Conditions of use).<br/>  
	</span><br/> 
	The Proforms Helpdesk offers information about the product, the opportunity to contact us (Direct Requests via our website or by email will be ignored) and downloads to upgrade packages, and other modules or plug-ins for Mooj Proforms.<br/>  
	<br/> 
	The help desk is under construction and is growing every day. When the construction is completed you will receive an update package that provides an automatic upgrade function.<br/>  
	<br/> 
	The domain restriction applies only to the help desk service. Proform\'s  functionality and portability are not affected.<br/> 
	<br/> 
	');
	define('M4J_LANG_SEARCH_IN',' Search in ');

?>
