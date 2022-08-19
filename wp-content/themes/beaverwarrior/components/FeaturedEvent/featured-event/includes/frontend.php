<?php
$wrapper_classes = [
  "class" => ["FeaturedEvent"],
  "id" => 'FeaturedEventBanner-'.$id,
];

?>


<section <?php echo spacestation_render_attributes($wrapper_classes); ?>>
<?php foreach ($settings->featured_events as $featured_event) : ?>
  <?php
$today = date("Y-m-d");
$compare = $featured_event->featured_event_date_to;
if($today>$compare){
  $displaynone ='displayNone';
}
else
{
  $displaynone ='';
}?>

<div class="FeaturedEvent-banner <?php echo $displaynone?>" id="bw-featured-banner-<?php echo esc_js($id); ?>">
<?php
$inputFrom = $featured_event->featured_event_date_from;
$inputTo = $featured_event->featured_event_date_to;
$dateF= strtotime($inputFrom);
$dateT= strtotime($inputTo);
//If From and To dates are same, show only one date
if($inputFrom==$inputTo){
$dateFrom =date("M d, Y", $dateF);
$dateTo ='';}
else{
$dateFromMonth =date("M", $dateF);
$dateToMonth =date("M", $dateT);
if ($dateFromMonth==$dateToMonth){
  $dateFrom =date("M d - ", $dateF);
  $dateTo =date("d, Y", $dateT);
}
else{
  $dateFrom =date("M d -", $dateF);
  $dateTo =date("M d, Y", $dateT);
}
}

?>
<div class="FeaturedEvent-details">
<div class="FeaturedEvent-type"><?php echo $featured_event->featured_event_type?></div>
<div class="FeaturedEvent-date">
<div class="FeaturedEvent-date_from"><?php echo $dateFrom?></div>
<div class="FeaturedEvent-date_to"><?php echo $dateTo?></div>
</div>
<div class="FeaturedEvent-title"><?php echo $featured_event->featured_event_main_title ?></div>
<div class="FeaturedEvent-description"><?php echo $featured_event->featured_event_description ?></div>
<a href="<?php echo $featured_event->featured_event_link?>" class="FeaturedEvent-learn_more">
<div class="FeaturedEvent-link_button"><?php echo $featured_event->featured_event_link_button ?></div></a>
</div>

<div class="FeaturedEvent-image">
<img src="<?php echo $featured_event->featured_event_image_src ?>" />
</div>
</div>

<?php endforeach; ?>

  </div>
</section>