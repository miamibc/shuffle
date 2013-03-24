<?php

/**
 * Description of shuffle
 *
 * @package     shuffle
 * @subpackage  shuffle
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.blackcrystal.net
 * @version
 * @since       Mar 24, 2013
 * @author      Sergei Miami <miami@blackcrystal.net>
 */
class Shuffle
{

  private $src = null,
          $dst = null,
          $type = null,
          $width = 0,
          $height = 0,
          $w,$h,
          $html = array(),
          $map = array();

  public function __construct($src = null)
  {
    if (!is_null($src))
      $this->load($src);
  }

  public function load($filename)
  {
    $this->map = array(); // clean shuffle map
    
    list($this->width, $this->height, $this->type) = getimagesize($filename); // get image params
    
    // load image by type
    switch ($this->type) {
      case 1: $this->src = imagecreatefromgif($filename); break;  //gif
      case 2: $this->src = imagecreatefromjpeg($filename); break; //jpeg
      case 3: $this->src = imagecreatefrompng($filename); break;  //png
    }
    
    return $this;
  }


  public function save($filename)
  {
    
    $id = uniqid('shuffle');
    
    file_put_contents($filename . '.html', <<<STYLE
 
<style type="text/css">
  #{$id} { display: inline; }
  #{$id} span { width: {$this->w}px; height: {$this->h}px; display: inline-block; background-image: url('{$filename}'); }
  #{$id} div { display: block; clear: both; }
</style>

<div id="{$id}">
{$this->html}
</div>

STYLE

    );

    imagejpeg($this->dst, $filename); //save new image

    return $this;
    
  }

  public function shuffle( $div = 2 )
  {
    
    $this->w = $w = $this->width/$div;
    $this->h = $h = $this->height/$div;
    
    $this->dst = imagecreatetruecolor($this->width, $this->height);
    
    // fill array with parts of source image
    for( $y = 0; $y < $div; $y++) 
      for( $x = 0; $x < $div; $x++) 
        $map[] = array($x,$y); 
      
    $this->html = '';    
    for( $sy = 0; $sy < $div; $sy++) {      
      $this->html .= '<div>';      
      for( $sx = 0; $sx < $div; $sx++) {    
        $elnum = array_rand( $map ); // get random part index
        list($dx, $dy) = $map[$elnum]; // get this part
        unset($map[$elnum]); // remove from stack
        $this->html .= '<span style="background-position: -'.$dx*$w.'px -'.$dy*$h.'px;"></span>';
        imagecopyresampled($this->dst,$this->src,$dx*$w,$dy*$h,$sx*$w,$sy*$h,$w,$h,$w,$h); // place it to dest image
      }      
      $this->html .= '</div>';
    }
    
    return $this;
    
  }
  
}
