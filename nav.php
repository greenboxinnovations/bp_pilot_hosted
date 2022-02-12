<?php
$nav_array = ['index','activity','transactions','customers','rbooks','payments','logout'];
// $nav_array = ['index','customers','payments','logout'];

echo'<div id="side_nav">';
	echo'<div id="side_nav_padding">';
		for($i=0;$i<sizeof($nav_array);$i++) {

			$nav_display = str_replace("_"," ",$nav_array[$i]);
			$nav_display = ucwords($nav_display);
			if ($nav_array[$i]=='index') {
				$nav_display = 'Home';
			}
			if( $nav_array[$i] == $active_page ) {
				echo '<div class="side_nav_single"><a href="'.$nav_array[$i].'.php" class="active">'.$nav_display.'</a></div>';
			}
			else{
				if ($nav_array[$i]=='logout') {

					echo '<div class="side_nav_single"><a href="exe/'.$nav_array[$i].'.php">'.$nav_display.'</a></div>';
				}else{
					echo '<div class="side_nav_single"><a href="'.$nav_array[$i].'.php">'.$nav_display.'</a></div>';
				}
			}
		}
	echo'</div>	';
echo'</div>';
?>