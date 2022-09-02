<?php
$wrapper_classess = [
  "class" => ["OtherEvents"],
  "id" => 'OtherEventsBanner-' . $id,
];

?>

<section <?php echo spacestation_render_attributes($wrapper_classess); ?>>
  <link href='https://css.gg/arrow-top-right.css' rel='stylesheet'>
  <div class="OtherEvents-select">
    <select class="OtherEvents-select--type">
    <option value="all">Event Type</option>
    <option value="SpaceX">SpaceX</option>
    <option value="Party">Party</option>
    <option value="Team">Team</option>
    </select>
  </div>
  <div class="OtherEvents-date_order">
  <?php foreach ($settings->other_eventse as $other_events) : ?>
    
    <?php
    $todayO = date("Y-m-d");
    $compareO = $other_events->other_events_date_to;
    if ($todayO > $compareO) {
      $displaynoneo = 'OtherEvents-displayNone';
    } else {
      $displaynoneo = '';
    }
    $inputFromO = $other_events->other_events_date_from;
    $dataDa = strtotime($inputFromO);
    $dataDate =date("y", $dataDa)+date("d", $dataDa)+date("m", $dataDa)*100;
?>


    <div class="OtherEvents-banner <?php echo $displaynoneo, $other_events->other_events_type?>" data-sort="<?php echo $dataDate?>" id="bw-other-banner-<?php echo esc_attr($id); ?>">
      <?php
      $inputFromO = $other_events->other_events_date_from;
      $inputToO = $other_events->other_events_date_to;
      $dateFO = strtotime($inputFromO);
      $dateTO = strtotime($inputToO);

      //If From and To dates are same, show only one date
      if ($inputFromO == $inputToO) {
        $dateFromO = date("M d, Y", $dateFO);
        $dateToO = '';
      } else {
        $dateFromMonthO = date("M", $dateFO);
        $dateToMonthO = date("M", $dateTO);
        if ($dateFromMonthO == $dateToMonthO) {
          $dateFromO = date("M d - ", $dateFO);
          $dateToO = date("d, Y", $dateTO);
        } else {
          $dateFromO = date("M d -", $dateFO);
          $dateToO = date("M d, Y", $dateTO);
        }
      }

      ?>
      <div class="OtherEvents-details">
        <!--<div class="OtherEvents-type"></div> -->
        <div class="OtherEvents-date">
          <div class="OtherEvents-date_from"><?php echo $dateFromO ?></div>
          <div class="OtherEvents-date_to"><?php echo $dateToO ?></div>
        </div>
        <div class="OtherEvents-title"><?php echo $other_events->other_events_main_title ?></div>
        <div class="OtherEvents-description"><?php echo $other_events->other_events_description ?></div>
        <div class="OtherEvents-link_button fl-button-wrap fl-button-width-auto fl-button-has-icon">
			<a href="<?php echo $other_events->other_events_link ?>" target="_blank" class="fl-button" role="button" rel="noopener">
							<span class="fl-button-text"><?php echo $other_events->other_events_link_button ?></span>
						<i class="OtherEvents-link_icon fl-button-icon fl-button-icon-after icon-arrowright-up" aria-hidden="true"></i>
			</a>
      
</div>
      </div>

      <div class="OtherEvents-image">
        <img src="<?php echo $other_events->other_events_image_src ?>" />
      </div>
    </div>
    </div>
  <?php endforeach; ?>


  </div>
</section>