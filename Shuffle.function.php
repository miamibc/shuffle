<?php

  function shuffle_image( $filename, $div = 2, $saveas = null )
  {
    
    
    list($sw, $sh) = getimagesize($filename);
    $src = imagecreatefromjpeg($filename);
    
    $w = round($sw/$div);
    $h = round($sh/$div);
    
    $dst = imagecreatetruecolor($sw, $sh);
    $map = array();
    
    // fill array with parts of source image
    for( $y = 0; $y < $div; $y++) 
      for( $x = 0; $x < $div; $x++) 
        $map[] = array($x,$y); 
      
    $html = '';    
    for( $sy = 0; $sy < $div; $sy++) {      
      $html .= '<div>';      
      for( $sx = 0; $sx < $div; $sx++) {    
        $elnum = array_rand( $map ); // get random part index
        list($dx, $dy) = $map[$elnum]; // get this part
        unset($map[$elnum]); // remove from stack
        $html .= '<span style="background-position: -'.$dx*$w.'px -'.$dy*$h.'px;"></span>';
        imagecopyresampled($dst,$src,$dx*$w,$dy*$h,$sx*$w,$sy*$h,$w,$h,$w,$h); // place it to dest image
      }      
      $html .= '</div>';
    }
        
    $filename = is_null($saveas) ? 'shuffle_'. $filename : $saveas;       
    $id = uniqid('shuffle');
    
    file_put_contents($filename . '.html', <<<STYLE
 
<style type="text/css">
  #{$id} { display: inline; }
  #{$id} span { width: {$w}px; height: {$h}px; display: inline-block; background-image: url('{$filename}'); }
  #{$id} div { display: block; clear: both; }
</style>

<div id="{$id}">
{$html}
</div>

STYLE

    );

    imagejpeg($dst, $filename); //save new image

}