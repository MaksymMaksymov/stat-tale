<!DOCTYPE html>
<html>
<?php 
	include_once("HeadView.html");
	include_once("View.php");
?>

<body> 	
    <?php 
        View::getHeader();
    ?>
	
    <div id="map-data" style="display: none;" 
        data-sprite=<?php echo $sprite ?>
        data-mode=<?php echo $mode ?> >
        <?php echo json_encode($map -> template_data);?>
    </div>
    <div id="tale-map-wrap">
        <canvas id="tale-map"></canvas>
    </div>
    <script src="../views/map/draw-map.js"></script>

  	<?php
        View::getFooter();
  	?>
</body>
</html>