<?php
/**
* @name MOOJ Proforms 
* @version 1.0
* @package proforms
* @copyright Copyright (C) 2008-2010 Mad4Media. All rights reserved.
* @author Dipl. Inf.(FH) Fahrettin Kutyol
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Please note that some Javascript files are not under GNU/GPL License.
* These files are under the mad4media license
* They may edited and used infinitely but may not repuplished or redistributed.  
* For more information read the header notice of the js files.
**/

	/**  ENGLISH VERSION. NEEDS TO BE TRANSLATED */


	defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Checkbox';
	$m4j_lang_elements[2]= 'Piihan Ya/ Tidak';
	$m4j_lang_elements[10]= 'Tanggal';
	$m4j_lang_elements[20]= 'Lajur Teks';
	$m4j_lang_elements[21]= 'Bidang Teks';
	$m4j_lang_elements[30]= 'Menu(pilihan tunggal)';
	$m4j_lang_elements[31]= 'Pilih Menu(pilihan tunggal)';
	$m4j_lang_elements[32]= 'Radiobuttons(single choice)';
	$m4j_lang_elements[33]= 'Checkbox Group(pilihan ganda)';
	$m4j_lang_elements[34]= 'Daftar(pilihan ganda)';
	
	
	define('M4J_LANG_FORMS','Form');
	define('M4J_LANG_TEMPLATES','Templat');
	define('M4J_LANG_CATEGORY','kategorI');
	define('M4J_LANG_CONFIG','konfigurasi');
	define('M4J_LANG_HELP','Info & Bantu');
	define('M4J_LANG_CANCEL','Batal');
	define('M4J_LANG_PROCEED','Lanjut');
	define('M4J_LANG_SAVE','Simpan');
	define('M4J_LANG_NEW_FORM','Form Baru');
	define('M4J_LANG_NEW_TEMPLATE','Templat Baru');
	define('M4J_LANG_ADD','Tambah');
	define('M4J_LANG_EDIT_NAME','Sunting nama dan deskripsi templat ini');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Templat Baru');
	define('M4J_LANG_TEMPLATE_NAME','Nama Templat ini');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Sunting nama templat ini');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Deskripsi singkat (untuk penggunaan internal. Dapat dikosongkan)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Sunting Deskripsi singkat');
	define('M4J_LANG_DELETE','Hapus');
	define('M4J_LANG_DELETE_CONFIRM','Anda benar2 akan menhapus item ini?');
	define('M4J_LANG_NEW_CATEGORY','Kategori Baru');
	define('M4J_LANG_NAME','Nama');
	define('M4J_LANG_SHORTDESCRIPTION','Deskripsi singkat');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Item');
	define('M4J_LANG_EDIT','Sunting');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Item -> Sunting');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Masukkan nama templat ini !');
	define('M4J_LANG_AT_LEAST_ONE','Item ini tak dapat dihapus!<br/>Minimal harus terdapat satu elemen di sini.');
	

		define('M4J_LANG_EDIT_ELEMENT','Sunting elemen Templat: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Masukkan nama kategori');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Masukkan alamat email yang valid atau biarkan kosong.<br/>');
	define('M4J_LANG_EMAIL','eMail');
	define('M4J_LANG_POSITION','Urutan');
	define('M4J_LANG_ACTIVE','Aktif');
	define('M4J_LANG_UP','Naik');
	define('M4J_LANG_DOWN','Turun');
	define('M4J_LANG_EDIT_CATEGORY','Sunting Kategori');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elemen2 dari Templat: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Masukkan elemen baru ke templat: ');
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Masukkan sebuah pertanyaan.');
	define('M4J_LANG_REQUIRED','Wajib');
	define('M4J_LANG_QUESTION','Pertanyaan');
	define('M4J_LANG_TYPE','Tipe');
	define('M4J_LANG_YES','Ya');
	define('M4J_LANG_NO','Tidak');
	define('M4J_LANG_ALL_FORMS','Semua Form');
	define('M4J_LANG_NO_CATEGORYS','Tanpa Kategori');
	define('M4J_LANG_FORMS_OF_CATEGORY','Form Kategori: ');
	define('M4J_LANG_PREVIEW','Tampilkan');
	define('M4J_LANG_DO_COPY','Duplikasi');
	define('M4J_LANG_COPY','Duplikasi');
	define('M4J_LANG_VERTICAL','Vertikal');
	define('M4J_LANG_HORIZONTAL','Horisontal');
	define('M4J_LANG_EXAMPLE','Contoh');
	define('M4J_LANG_CHECKBOX','Tombol');
	define('M4J_LANG_DATE','Tanggal');
	define('M4J_LANG_TEXTFIELD','Lajur Teks');
	define('M4J_LANG_OPTIONS','Pilihan Tertentu');
	define('M4J_LANG_CHECKBOX_DESC','Pertanyaan sederhana Ya/ Tidak.');
	define('M4J_LANG_DATE_DESC','User harus memasukkan tanggal.');
	define('M4J_LANG_TEXTFIELD_DESC','User harus memasukkan Teks tersendiri.');
	define('M4J_LANG_OPTIONS_DESC','User memilih satu atau lebih jawaban dari antara item2 yang ditentukan. ');
	define('M4J_LANG_CLOSE_PREVIEW','Tutup Tampilan');
	define('M4J_LANG_Q_WIDTH','Lebar kolom pertanyaan. (kiri)');
	define('M4J_LANG_A_WIDTH','Lebar kolom jawaban. (kanan)');
	define('M4J_LANG_OVERVIEW','Overview');
	define('M4J_LANG_UPDATE_PROCEED','& Lanjut');
	define('M4J_LANG_NEW_ITEM','Item Baru');
	define('M4J_LANG_EDIT_ITEM','Sunting Item');
	define('M4J_LANG_CATEGORY_NAME','Nama Kategori');
	define('M4J_LANG_EMAIL_ADRESS','Alamat Email');
	define('M4J_LANG_ADD_NEW_ITEM','Tambah Item baru:');
	define('M4J_LANG_YOUR_QUESTION','Pertanyaan Anda');
	define('M4J_LANG_REQUIRED_LONG','Wajib?');
	define('M4J_LANG_HELP_TEXT','Tks Bantuan. Berikan petunjuk kepada user mengenai pertanyaan anda.(not essential)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Tipe Tombol:');
	define('M4J_LANG_ITEM_CHECKBOX','Checkbox.');
	define('M4J_LANG_YES_NO_MENU','Menu Ya/ Tidak.');
	define('M4J_LANG_YES_ON','Ya/On.');
	define('M4J_LANG_NO_OFF','Tidak/Off.');
	define('M4J_LANG_INIT_VALUE','Nilai Awal:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Tipe Lajur Teks:');
	define('M4J_LANG_ITEM_TEXTFIELD','Lajur Teks');
	define('M4J_LANG_ITEM_TEXTAREA','Bidang Teks');
	define('M4J_LANG_MAXCHARS_LONG','Maks. Karater:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Penataan Visual:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Lebar dalam Pixel</b> <br/>(Add \'%\' untuk persentase. Kosong = Automatis Menyesuaikan)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Jumlah baris yang tampak:</b><br/> (Hanya untuk Bidang Teks)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menu</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Radiobuttons</b>');
	define('M4J_LANG_LIST_SINGLE','<b>List</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(single choice)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Checkbox Group</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Daftar</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pilihan ganda, dengan \'CTRL\' & tombol kiri mouse)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Pilihan Tunggal (Hanya dapat memilih satu item):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Pilihan Ganda (Dapat memilih lebih dari satu Item):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Tipe Pilihan:');
	define('M4J_LANG_ROWS_LIST','<b>Jumlah baris yang tampak:</b><br/> (HAnya untuk Daftar)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Perletakan: </b> <br/>(Hanya untuk Grup Radiobutton dan Checkbox)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Tentukan jawaban2.<br/>Lajur2 kosong akan diabaikan.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Teks Intro. Hanya akan ditampilkan di situs2 kategori.');
	define('M4J_LANG_TITLE','Title');
	define('M4J_LANG_ERROR_NO_TITLE','Masukkan judul.');
	define('M4J_LANG_USE_HELP','Teks Bantuan untuk baloontips frontend');
	define('M4J_LANG_TITLE_FORM','Judul Form');
	define('M4J_LANG_INTROTEXT','Teks Intro');
	define('M4J_LANG_MAINTEXT','Teks Utama');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Teks Intor Email. (Hanya ditampilkan di email2.)');
	define('M4J_LANG_TEMPLATE','Templat');
	define('M4J_LANG_LINK_TO_MENU','Tautan ke Menu');
	define('M4J_LANG_LINK_CAT_TO_MENU','Tautan kategori ini ke menu');
	define('M4J_LANG_LINK_TO_CAT','Tautan Kategori: ');
	define('M4J_LANG_LINK_TO_FORM','Tautan Form: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Tautan untuk menampilkan semua Form2 tanpa kategori ');
	define('M4J_LANG_LINK_TO_ALL_CAT','TAutan ke tampilan \'Semua Kategori\'');
	define('M4J_LANG_CHOOSE_MENU','Pilih menu untuk tautan ke: ');
	define('M4J_LANG_MENU','Menu: ');
	define('M4J_LANG_NO_LINK_NAME','Masukkan nama tautan.');
	define('M4J_LANG_PUBLISHED','Publik:');
	define('M4J_LANG_PARENT_LINK','Tautan Induk');
	define('M4J_LANG_LINK_NAME','Nama Tautan');
	define('M4J_LANG_ACCESS_LEVEL','Level Akses:');
	define('M4J_LANG_EDIT_MAIN_DATA','Sunting Data Dasar');
	define('M4J_LANG_LEGEND','Legenda');
	define('M4J_LANG_LINK','Tautan');
	define('M4J_LANG_IS_VISIBLE',' publik');
	define('M4J_LANG_IS_HIDDEN',' non-publik');
	define('M4J_LANG_FORM','Form');
	define('M4J_LANG_ITEM','Item');
	define('M4J_LANG_IS_REQUIRED','Wajib');
	define('M4J_LANG_IS_NOT_REQUIRED','Tidak Wajib');
	define('M4J_LANG_ASSIGN_ORDER','Urutan');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Pengurutan tidak dimungkinkan untuk  \'Semua Form\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Sunting Form');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Publik! Klik= Non-publik');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Nonpublik! Klik= Publik');
	define('M4J_LANG_HOVER_REQUIRED_ON','Wajib! Klik= Tidak Wajib');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Tidak Wajib! Klik= Wajib');
	define('M4J_LANG_DESCRIPTION','Deskripsi');
	define('M4J_LANG_AREA','Bidang');
	define('M4J_LANG_ADJUSTMENT','Konfigurasi');
	define('M4J_LANG_VALUE','Nilai');
	define('M4J_LANG_MAIN_CONFIG','Konfigurasi Utama:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Anda dapat mengkonfigurasikan semua seting di sini. Klik Reset bila ingin mereset semua setting2 utama (termasuk CSS) ke default.');
	define('M4J_LANG_CSS_CONFIG','Seting CSS:');
	define('M4J_LANG_CSS_CONFIG_DESC','Seting2 ini diperlukan untuk tampilan visual pada frontend. Bila anda tidak berpengalaman dengan memasukkan CSS eksternal (sendiri), jangan merubah nilai2 ini !');
	define('M4J_LANG_RESET','Reset');

	define('M4J_LANG_EMAIL_ROOT', 'Alamat Email Utama: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Maksimum Jawaban <br/> Tentukan Pilihan: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Lebar Tampilan: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Tinggi Tampilan: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Waktu Tampilan Captcha (dalam mnt): ' );
	define('M4J_LANG_HELP_ICON', 'Ikon Bantu: ' );
	define('M4J_LANG_HTML_MAIL', 'Email HTML: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Tampilkan Legenda: ' );

	define('M4J_LANG_EMAIL_ROOT_DESC', 'Amalam email utama digunakan bila kategori maupun form tidak menentukan alamat email.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Nilai ini membatasi jumlah maksimum jawaban untuk sebuah item \'Pilihan Tertentu\'. Nilai ini harus numerik.' );
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Lebar tampilan templat. Nilai ini hanya digunakan bila anda tidak menetapkan lebar tampilan pada data dasar dari sebuah templat.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Tinggi tampilan templat. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Milik frontend. Nilai ini menentukan waktu maksimum validitas captcha. Bila nilai ini terlampaui, sebuah kode captcha baru harus dimasukkan walaupun kode sebelumya valid.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Tentukan warna ikon bantu.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Bila anda menginginkan menerima email2 HTML pilih ya. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Bila anda ingin menampilkan sebuah legenda di backend pilih ya.' );

	define('M4J_LANG_CLASS_HEADING', 'Headline Utama:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Teks Header' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Daftar- Wrap ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Daftar- Headline' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Daftar- Teks Intro ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Form- Wrap ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Form- Tabel ' );
	define('M4J_LANG_CLASS_ERROR', 'Teks Eror' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Wrap Tombol Kirim' );
	define('M4J_LANG_CLASS_SUBMIT', 'Tombol Kirim ' );
	define('M4J_LANG_CLASS_REQUIRED', 'Wajib * css ' );

	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Headline dari sebuah situs ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Konten setelah headline. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap dari daftar item.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Headline dari daftar item. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Teks Intro dari daftar item. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap dari bidang form. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Tabel dimana semua item form diotampilkan.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS class dari pesan2 kesalahan. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap dari tombol kirim ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS class dari tombol kirim. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS class dari \' <b>*</b> \' karakter untuk mendeklarasikan lajur2 wajib.' );

	define('M4J_LANG_INFO_HELP','Info dan Bantu');

	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Tehnik Captcha: ' );
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Biasa');

	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','Konfigurasi berhasil direset.');
		define('M4J_LANG_CONFIG_SAVED','Konfigurasi berhasil disimpan.');
		define('M4J_LANG_CAPTCHA_DESC', ' Dimungkinkan terjadi masalah pada css-captcha-standar dan beberapa server atau template. Untuk kasus ini anda memiliki alternatif untuk memilih antara css-captcha-standar dan captcha biasa. Bila captcha biasa tidak menyelesaikan masalah, maka pilih Spesial' );
		define('M4J_LANG_SPECIAL','Spesial');


	define('M4J_LANG_MAIL_ISO','Mail Charset');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Lampiran';
	define('M4J_LANG_ATTACHMENT','Lampiran File');
	define('M4J_LANG_ATTACHMENT_DESC','User dapat mengirim file memalui form.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Masukkan parameter2 untuk lajur transfer file ini:');
	define('M4J_LANG_ALLOWED_ENDINGS','Ekstensi2 file yang disetujui.');
	define('M4J_LANG_MAXSIZE','Ukuran File Maksimum.');
	define('M4J_LANG_BYTE','Byte');
	define('M4J_LANG_KILOBYTE','Kilobyte');
	define('M4J_LANG_MEGABYTE','Megabyte');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Masukkan semua ekstensi file tanpa titik (.) dan pisahkan dengan <b>kom</b>. Bila lajur anda kosongkan, maka semua ekstensi atau ukuran file akan disetujui. Untuk memudahkan, anda dapat memilih dari ekstensi2 dibawah yang secara otomatis akan dimasukkan.');
	define('M4J_LANG_IMAGES','Gambar');
	define('M4J_LANG_DOCS','Dokumen');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');
    define('M4J_LANG_DATA','Data');
	define('M4J_LANG_COMPRESSED','Kompresi');
	define('M4J_LANG_OTHERS','Lain2');
	define('M4J_LANG_ALL','Semua');

	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','Dari Nama');
	define('M4J_LANG_FROM_EMAIL','Dari Email');
	define('M4J_LANG_FROM_NAME_DESC','Masukkan sebua nama dari untuk email2 yang hendak anda capai<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Masukkan sebuah nama dari alamat email untuk email2 yang hendak anda capai.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Perhatian! Semua form yang mengandung templat ini juga akan dihapus!');
	
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