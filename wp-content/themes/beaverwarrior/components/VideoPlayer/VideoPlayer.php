<?php

$player_option = get_sub_field('video_source');

$player_id = uniqid();
$player_source_id = uniqid();

$bg = get_sub_field('video_bg');

?>

<section class="ContentSection ContentSection--<?php the_sub_field('video_bg_color'); ?> ContentSection--video<?php if ($bg !== false) { ?> ContentSection--with_background<?php } ?>">
    <?php if ($bg !== false) { ?>
        <div class="ContentSection-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
    <?php } else { ?>
        <div class="ContentSection-background ContentSection--<?php the_sub_field('video_bg_color'); ?>-background"></div>
    <?php } ?>
    <div class="Container ContentSection-player ContentSection--video-player">
        <div class="VideoPlayer VideoPlayer--<?php echo $player_option; ?>" data-videoplayer="<?php echo $player_option; ?>" id="<?php echo $player_id; ?>">
            <div class="VideoPlayer-video VideoPlayer-video--sixteen_by_nine">
                <?php switch ($player_option) {
                    case "html5":
                        ?>
                            <video>
                                <?php while (have_rows("video_source_files")) {
                                    the_row("video_source_files");

                                    $file = get_sub_field("video_source_file");

                                    ?><source src="<?php echo $file["url"] ?>" type="<?php echo $file["mime_type"]; ?>"><?php
                                } ?>
                            </video>
                        <?php
                        break;
                    case "youtube":
                        ?><iframe src="https://www.youtube.com/embed/<?php the_sub_field("video_id"); ?>?enablejsapi=1&controls=0" frameborder="0" allowfullscreen id="<?php echo $player_source_id; ?>"></iframe><?php
                        break;
                    case "vimeo":
                        ?><iframe src="https://player.vimeo.com/video/<?php the_sub_field("video_id"); ?>" width="640" height="363" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="<?php echo $player_source_id; ?>"></iframe><?php
                        break;
                    default:
                        break;
                    } ?>
            </div>
            <div class="VideoPlayer-controls">
                <button type="button" class="VideoPlayer-play_pause" data-videoplayer-playpause>
                    <span class="sr-only VideoPlayer-play_text">Play</span>
                    <span class="sr-only VideoPlayer-pause_text">Pause</span>
                </button>
                <button type="button" class="VideoPlayer-scrubber" data-videoplayer-scrubber>
                    <div class="VideoPlayer-scrubber_range"></div>
                    <div class="VideoPlayer-scrubber_fill" data-videoplayer-scrubberfill></div>
                    <div class="VideoPlayer-scrubber_knob" data-videoplayer-scrubberknob></div>
                </button>
                <button type="button" class="VideoPlayer-mute" data-videoplayer-mute>
                    <span class="sr-only VideoPlayer-mute_text">Mute</span>
                    <span class="sr-only VideoPlayer-unmute_text">Unmute</span>
                </button>
            </div>
        </div>
    </div>
</section>