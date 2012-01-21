CREATE TABLE `#__flippingbook_books` (
  `id` int(11) NOT NULL auto_increment,
  `alias` text NOT NULL,
  `allow_pages_unload` tinyint(1) NOT NULL,
  `always_opened` tinyint(1) NOT NULL,
  `auto_flip_size` int(4) NOT NULL,
  `background_color` varchar(10) NOT NULL,
  `background_image` varchar(255) NOT NULL,
  `background_image_placement` varchar(10) NOT NULL,
  `book_height` varchar(6) NOT NULL,
  `book_width` varchar(6) NOT NULL,
  `category_id` int(6) NOT NULL,
  `center_book` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` int(11) NOT NULL default '0',
  `created` datetime NOT NULL,
  `description` text NOT NULL,
  `direction` VARCHAR( 6 ) NOT NULL,
  `download_size` varchar(100) NOT NULL,
  `download_title` varchar(100) NOT NULL,
  `download_url` varchar(255) NOT NULL,
  `dynamic_shadows_depth` varchar(5) NOT NULL,
  `emailIcon` tinyint(1) NOT NULL,
  `first_last_buttons` tinyint(1) NOT NULL,
  `first_page` int(4) NOT NULL,
  `flash_height` varchar(6) NOT NULL,
  `flash_width` varchar(6) NOT NULL,
  `flip_corner_style` varchar(20) NOT NULL,
  `frame_color` VARCHAR (10) NOT NULL,
  `frame_width` INT( 4 ) NOT NULL,
  `fullscreen_enabled` tinyint(1) NOT NULL,
  `fullscreen_hint` text NOT NULL,
  `go_to_page_field` tinyint(1) NOT NULL,
  `hardcover` tinyint(1) NOT NULL,
  `hits` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `navigation_bar` varchar(255) NOT NULL,
  `navigation_bar_placement` varchar(10) NOT NULL,
  `new_window_height` int(4) NOT NULL,
  `new_window_width` int(4) NOT NULL,
  `open_book_in` int(4) NOT NULL,
  `ordering` int(6) NOT NULL,
  `page_background_color` varchar(10) NOT NULL,
  `preview_image` varchar(255) NOT NULL,
  `print_enabled` tinyint(1) NOT NULL,
  `printIcon` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `scale_content` tinyint(1) NOT NULL,
  `show_book_description` tinyint(1) NOT NULL,
  `show_book_title` tinyint(1) NOT NULL,
  `show_pages_description` tinyint(1) NOT NULL,
  `show_slide_show_button` tinyint(4) NOT NULL,
  `show_zoom_hint` tinyint(1) NOT NULL,
  `slideshow_auto_play` tinyint(1) NOT NULL,
  `slideshow_button` tinyint(1) NOT NULL,
  `slideshow_display_duration` int(5) NOT NULL,
  `sound_control_button` int(1) NOT NULL,
  `static_shadows_depth` varchar(5) NOT NULL,
  `static_shadows_type` varchar(15) NOT NULL,
  `title` text NOT NULL,
  `transparent_pages` tinyint(1) NOT NULL,
  `zoom_enabled` tinyint(1) NOT NULL,
  `zoom_image_height` int(5) NOT NULL,
  `zoom_image_width` int(5) NOT NULL,
  `zoom_ui_color` varchar(10) NOT NULL,
  `zooming_method` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `#__flippingbook_books` VALUES (1, 'flippingbook-in-action', 0, 0, 75, 'dedede', 'abstract_background_blue.jpg', 'fit', '400', '300', 1, 1, 0, 0, '2009-01-01 00:00:00', 'This sample book demonstrates several ways of using the component.<br />   FlippingBook engine works with <strong>JPG, PNG, GIF and SWF</strong> (Flash) files. The JPG is convenient for creating picture albums, PNG or GIF format - for text, screenshots, drafts. The SWF format is convenient for presentations with animation, video, links etc.<em> You can modify this text in administration back-end  (Components &gt; FlippingBook &gt; Manage Books &gt; FlippingBook In Action  &gt; Description).</em>', 'LTR', '100 Kb', 'Download Book', 'http://localhost/my-book.pdf', '2', 1, 1, 1, '500', '100%', 'manually', 'FFFFFF', '0', 1, '',  1, 1, 1, '2008-01-01 00:00:00', 'navigation.swf', 'bottom', 640, 640, 1, 1, 'EEEEEE', 'book_preview.png', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 5000, 1, '1', 'Asymmetric', 'FlippingBook In Action', 0, 1, 800, 600, '8f9ea6', 0);

CREATE TABLE `#__flippingbook_categories` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(6) NOT NULL,
  `checked_out_time` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL,
  `emailIcon` tinyint(1) NOT NULL,
  `printIcon` tinyint(1) NOT NULL,
  `columns` int(2) NOT NULL,
  `preview_image` text NOT NULL,
  `show_title` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `#__flippingbook_categories` VALUES (1, 'Default Category', 'default-category', 'Category description', 1, 1, 0, 0, 1, 1, 2, 'category_preview.png', 1);

CREATE TABLE `#__flippingbook_config` (
  `id` int(9) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `#__flippingbook_config` VALUES (1, 'categoryListTitle', 'FlippingBook Categories');
INSERT INTO `#__flippingbook_config` VALUES (2, 'closeSpeed', '3');
INSERT INTO `#__flippingbook_config` VALUES (3, 'columns', '3');
INSERT INTO `#__flippingbook_config` VALUES (4, 'downloadComplete', 'Complete');
INSERT INTO `#__flippingbook_config` VALUES (5, 'dropShadowEnabled', '1');
INSERT INTO `#__flippingbook_config` VALUES (6, 'emailIcon', '1');
INSERT INTO `#__flippingbook_config` VALUES (7, 'flipSound', 'newspaper.mp3');
INSERT INTO `#__flippingbook_config` VALUES (8, 'gotoSpeed', '3');
INSERT INTO `#__flippingbook_config` VALUES (9, 'hardcoverSound', 'photo_album.mp3');
INSERT INTO `#__flippingbook_config` VALUES (10, 'moveSpeed', '2');
INSERT INTO `#__flippingbook_config` VALUES (11, 'preloaderType', 'Progress Bar');
INSERT INTO `#__flippingbook_config` VALUES (12, 'printIcon', '1');
INSERT INTO `#__flippingbook_config` VALUES (13, 'printTitle', 'Print pages');
INSERT INTO `#__flippingbook_config` VALUES (14, 'rigidPageSpeed', '5');
INSERT INTO `#__flippingbook_config` VALUES (15, 'theme', 'white.css');
INSERT INTO `#__flippingbook_config` VALUES (16, 'zoomHint', 'Double click to zoom in');
INSERT INTO `#__flippingbook_config` VALUES (17, 'zoomOnClick', '1');
INSERT INTO `#__flippingbook_config` VALUES (18, 'version', '1.5.10');

CREATE TABLE `#__flippingbook_pages` (
  `id` int(11) NOT NULL auto_increment,
  `file` varchar(255) NOT NULL,
  `book_id` int(4) NOT NULL default '0',
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `link_url` text NOT NULL,
  `zoom_url` text NOT NULL,
  `zoom_height` int(4) not null default '800',
  `zoom_width` int(4) not null default '600',
  `checked_out_time` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

INSERT INTO `#__flippingbook_pages` VALUES (1, 'my-book_01.swf', 1, '<strong>Page 1. New features:</strong><br />- Hard cover<br />- Dynamic centering of the book<br />- Full-screen mode<br />- New zooming method<br />- Easily customizable navigation bar<br /><em>You can modify this text in administration back-end (Components &gt; FlippingBook &gt; Manage Pages &gt; page properties &gt; Description).</em>', 1, 1, '', '', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (2, 'my-book_02.jpg', 1, '<strong>Page 2. Create your own web-publications.</strong> There&rsquo;s nothing easier than creating a web-magazine, newspaper or booklet now. The zooming function enables your visitors to view even the smallest text. The batch adding function helps create books with several mouse-clicks. ', 2, 1, '', 'my-book_zoom_02.jpg', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (3, 'my-book_03.jpg', 1, '<strong>Page 3. New navigation bar</strong> is based on the flash technology. You can download the navigation bar source file from our web-site and change its look with the help of Adobe Flash. Navigation panel helps you switch to the full-screen mode, zoom and print pages, find the needed page quickly and even download the pre-prepared offline version of the book in the pdf-format, for example.', 3, 1, '', 'my-book_zoom_03.jpg', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (4, 'my-book_04.jpg', 1, '<strong>Page 4-5.</strong> Many people prefer selecting the products by printed catalogues instead of browsing through many trivial web-pages. By using our FlippingBook technology you can create an illusion of having a 3D catalogue before you.<br />You can attach individual link that will be displayed on the navigation bar to each page. This feature allows you to place the Buy Now button leading to the online shop under each page, or Download button that enables visitors to save the file attached to the page.<br />If you want to set one description for a whole spread &ndash; just leave a blank description field for one page.', 4, 1, '/product-in-a-shop.html', 'my-book_zoom_04.jpg', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (5, 'my-book_05.jpg', 1, '', 5, 1, '/product-in-a-shop.html', 'my-book_zoom_05.jpg', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (6, 'my-book_06.jpg', 1, '<strong>Page 6.</strong> Create photo albums that attract attention, surprise your visitors! You do not have to be a computer expert or a web-designer to create photo albums. This component is very easy to use: all you need is to create images of the right size, upload them to the server and place them in the book.', 6, 1, '', 'my-book_zoom_06.jpg', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (7, 'my-book_07.jpg', 1, '<strong>Page 7.</strong> Portfolio created with the help of FlippingBook technology will impress your visitors and potential customers and stay in their memory for a long time. For example, if you are a web-designer, you can place a screenshot of a web-site on the page and the <em>Visit site</em> link under the page.', 7, 1, 'http://page-flip-tools.com', 'my-book_zoom_07.jpg', 800, 600, 0, 0);
INSERT INTO `#__flippingbook_pages` VALUES (8, 'my-book_08.jpg', 1, '<strong>Page 8.</strong> If you are experiencing problems with FlippingBook, feel free to contact us. In your message, please describe your problem (or attach the screenshot), detail your order number, the email address that you used for the order and site URL with FlippingBook installed. You can find contact information on our site.', 8, 1, '', 'my-book_zoom_08.jpg', 800, 600, 0, 0);