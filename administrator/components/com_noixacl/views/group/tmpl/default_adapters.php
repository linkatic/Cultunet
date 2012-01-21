<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$adaptersList = $this->Adapters->listAdapters();
$totalAdapters = count($adaptersList);
?>
<div id="mytree">
    <?php
    for($n = 1; $n <= $totalAdapters ; $n++){
            $adapter = $adaptersList[ $n - 1 ];
            $numAdapter = $n-1;
            echo "<div class=\"mooTree_node\" onclick=\"javascript: defaultAdaptersMenuExecute('{$numAdapter}','{$totalAdapters}');\">
                <div></div>
                <div style=\"background-image: url(components/com_noixacl/assets/mootree.gif); background-position: -162px 0px;\" class=\"mooTree_img\"></div>
                <div class=\"mooTree_text\">{$adapter->title}</div>
            </div>";
    }
    ?>
</div>