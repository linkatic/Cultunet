<?php

/**
* @author: GavickPro
* @copyright: 2008
**/

/**
	Plik szablonu dla zadania stats	
	
	Odpowiada ono za wy�wietlenie pomocy komponentu
	
	ToDo:
	 mo�na to jako� �adnie zaprezentowa� na przyk�ad
	 z u�yciem MooTools itp. Koncepcji jest sporo
	 i warto jak�� jedn� ciekaw� wybra� :)	
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<style type="text/css">
.article_news img{
	float: left;
	margin: 20px;
}

h2{
	font-size: 24px;
}

.wrapperr{
	width: 600px;
	margin: 0 auto;
}
</style>

<h1><?php echo JText::_('NEWS_HEADER'); ?></h1>
<hr />
<div class="wrapperr">
<?php echo $results; ?>
</div>