<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-20">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>

<?php
    if($this->menu) {
?>
<div class="span-6 last">
	<div id="sidebar">
            <ul class="list-group">
                <li class="list-group-item list-group-item-info"><strong>Menu</strong></li>
                <?php 
                    foreach($this->menu as $item) {
                        //var_dump($item);
                        //var_dump($item['url']);
                ?>
                <a href="<?php echo isset($item['url']['id']) ? Yii::app()->createUrl($item['url'][0], array('id'=>$item['url']['id'])) : Yii::app()->createUrl($item['url'][0]); ?>" class="list-group-item"><?php echo $item['label']; ?></a>
                <?php
                    }
                ?>
            </ul>
	</div><!-- sidebar -->
</div>
<?php
    }
?>
<?php $this->endContent(); ?>