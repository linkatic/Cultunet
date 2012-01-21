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
	
	/**  VERSI INDONESIA. */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>


<table width="944" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="400" height="309" align="left" valign="top"><img src="components/com_mad4joomla/images/mad4media-3d.png" width="400" height="309"></td>
        <td align="left" valign="top"><h3>Mad4Joomla Mailforms V <?PHP echo M4J_VERSION_NO; ?></h3>
          Komponen ini dibangun oleh  Dipl. Informatiker (similar to MSc) Fahrettin Kutyol untuk Mad4Media.<br>
          Mad4Media mengembangkan piranti lunak dibawah aspek dari User Centered Design. Produk2 dan proyek2 kami didisain berdasarkan orientasi-user untuk mencapai ergonomis yang masksimum (daya-guna). Disampin pengkodean dengan Java dan PHP kami menawarkan pengembangan ekstensi individu untuk Joomla atau osCommerce ke pelanggan2 kami. Bila anda tertarik akan pelayanan kami, anda dapat menghubungi kami melalui Homepage kami: <a href="http://www.mad4media.de" target="_blank">Mad4Media</a><br>
          <br>
          <strong>Lisensi dan Garansi</strong><br>
          Mad4Joomla Mailforms dipublikasikan dibawah lisensi GNU GPL. Tidak ada garansi atas fungsionalitas atau kelengkapan.  Mad4Media tidak bertnggung-jawab atas kerusakan yang disebabkan oleh komponen ini..<br>
          <br>
          <strong>Komponen2 Open Source Yang Dimplementasikan:</strong><br>
          <a href="http://www.dynarch.com/projects/calendar/" target="_blank">jsCalendar</a> - LGPL<br>
          Ikon2 dari <a href="http://www.dryicons.com" target="_blank">dryicons.com</a> - Dengan Lisensi Creative Commons Attribution 3.0<br>
          <a href="http://www.dhtmlgoodies.com" target="_blank">Balloontip</a> - LGPL <br>
          <br>
          <br></td>
      </tr>
    </table>

	    <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="top"><h3>Tentang Mad4Joomla Mailforms</h3>
		      <p align="left">Mad4Joomla merupakan komponen yang mudah digunakan untuk membuat form2 email.<br />
	          Preferensi dari produk ini adalah kegunaan yang lebih baik, klasifikasi pada kategori2, bekerja dengan templat2, teks bantuan untuk lajur2 form, tehnik routing khusus menuju alamat2 email, integrasi konten di formpages dan tehnik baru captcha yang sangat khusus. Sehingga memungkinkan untuk membangun sistim kontak yang kompleks dan besar. Contoh: Pekerjaan, Reservasi dsb. </p>
		      <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" height="100%" align="left" valign="top"><h3>Mad4Joomla Home</h3>
                  <p>Untuk itu andan akan mendapatkan informasi secara eksplisit di beranda mad4media.
                    <br />
                    Kami menghargai peterjemahan. Secara terpisah anda dapat mendownload file2 bahasa pada
                    alamat berikut. Kirimkan terjemahan2 anda kepada kami. Kami akan melmpirkannya pada paket komponen dan mempublikasikannya.<br />
                    Anda dapat membuka halaman proyek di: <a href="http://www.mad4media.de/mad4joomla-mailforms.html" target="_blank">www.mad4media.de/mad4joomla-mailforms.html
                  </a></p>
                  <p>Para Peterjemah akan mendapatkan sebuah tautan balik (seperti dibawah ini) ke Website mereka. <br />
                  <h3>Terjemahan2</h3>
                    Inggris, Jerman oleh <a href="www.mad4media.de" target="_blank">mad4media</a><br />
                    Portugis Frontend oleh
                    <a href="mailto:tecnicoisaias@yahoo.com.br">Isaias Santana
                    </a><br />
                    <br />
                  </p>
                </td>
              </tr>
              </table>			<h3>&nbsp;</h3></td>
            <td width="52%" align="left" valign="top"><h3>Petunjuk Memulai <br />
            </h3>
              <p><strong>1.Langkah:</strong><br />
                Anda memerlukan kategori? <br />
                Mis. Bila anda ingin mempublikasikan beberapa tawaran pekerjaan, disarankan untuk membuat kategori dengan nama &quot;lowongan&quot;. Dengan mengaplikasikan kategori anda dapat menambah konten yang akan ditampilkan pada judul dari halaman kategori. Bila form tidak memiliki sebuah alamat email tujuan, maka alamat kategori yang akan digunakan. Bila anda tidak mengaplikasikan sebuah alamat email tujuan pada sebuah kategori, maka akan digunakan alamat email utama (konfigurasi).
                <br />
                <br />
                <strong>2. Langkah:</strong><br />
              Mengaplikasikan satu atau lebih templat.<br />
              Anda dapat memasukkan sebuah deskripsi singkat pada bidang data dasar. Ini berguna sebagai pengenal templat. Sngat penting memasukkan lebar dan tinggi kolom2 tabel form. Pada langkah berikutnya anda perlu mengaplikasian lajur2 form. Anda dapat menambahkan teks bantuan ke lajur2 yang akan ditampilkan di frontend dengan melambaikan mause di atas teks (mouseover). <br />
              <br />
                <strong>3.Langkah</strong><br />
                Mengaplikasikan form2.<br />
                Masukkan sebuah judul dan buat sebuah kategori. Bila anda tidak memerlukan/ membuat kategori pilih &quot;Tanpa kategorl&quot;.<br />
                Selanjutnya anda harus menetapkan sebuah templat. Bil anda tidak menyisipkan alamat email tujuan, email2 akan dikirim ke alamat tujuan kategori2. Bila tidak terdapat alamat tujuan kategori, maka akan digunakan alamat utama.
                <br />
                Dibawah &quot;captcha&quot; anda dapat emilih apakah anda ingin menggunakan pemeriksaan keamanan untuk menghindari bot spamm. <br />
                Sebuah teks intro hanya akan ditampilkan pada daftar kategor. <br />
                Teks utama hanya akan ditampilkan pada halaman2 form<br />
                Teks intro Email merupakan petunjuk untuk anda sendiri. Ini hanya tampak pada email2 jawaban.
                <br />
                <br />
                <strong>4.Langkah</strong><br />
                Membuat Tautan.<br />
                Pada halaman ringkasan form di backend anda dapat mentautkan form2 dan kategori2 ke sebuah menu. Anda juga dapat mentautkan &quot;Semua Form&quot; dan &quot;Tanpa Kategori&quot; ke sebuah menu.</p>
            </td>
          </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>

