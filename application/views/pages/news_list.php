<?php
foreach($news_list as $item) {
?>
    <p><a href="<?php echo base_url('intro/news/'.$item['id'])?>"><?php echo $item['title'];?></a></p>
<?php
}
?>
