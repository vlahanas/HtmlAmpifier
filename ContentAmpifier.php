<?php

namespace Ampifier;


class ContentAmpifier
{

  /*
   * Given an HTML content, this function will return the equivalent AMP markup
   */
  public function ampify($html = "") {


    // final step, removing all inline styles from initial html
    $html = $this->removeInlineStyles($html);

    return $html;
  }


  /*
   * Inline styles are not allowed according to AMP specifications
   * This function will remove all inline styles from your markup
   */
  private function removeInlineStyles($content) {

    // find style="..." and remove it from original content
    $content = preg_replace('/style=\\"[^\\"]*\\"/', '', $content);

    return $content;
  }


  /*
   * Extracts and returns the inline styles of an html element
   * This function comes in handy if you want to fetch specific styles from an
   * element before "ampification", for instance if you want to fetch the
   * dimenions of images (<img/>)
   */
  private function getElementStyles($elementHtml = "") {

    /// use this to extract image styles (before removing them from html document)
    preg_match_all('/style=\\"[^\\"]*\\"/', $elementHtml, $inlineStyles, PREG_SET_ORDER);

    return $inlineStyles;
  }


  /*
   * Extracts height and width from inline styles and returns their values
   *
   * Note: If no width or height is found, "1" will be returned instead
   * This way, if one is using layout="responsive" for <amp-img> elements, the
   * output of this function could directly be used
   */
  private function getImageDimensions($imageHtml = "") {

    // get the inline image styles
    $imgStyles = $this->getElementStyles($imageHtml);

    // fetch width attribute, if it exists
    preg_match('/[^-]width[: ]+([0-9]+)/', $imageHtml, $widthStyles);
    if (sizeof($widthStyles) > 0) {
      $width = $widthStyles[1];
    } else {
      $width = 1;
    }

    // fetch height attribute, if it exists
    preg_match('/[^-]height[: ]+([0-9]+)/', $imageHtml, $heightStyles);
    if (sizeof($heightStyles) > 0) {
      $height = $heightStyles[1];
    } else {
      $height = 1;
    }

    $htmlStr = 'width="'.$width.'" height="'.$height.'"';

    return ['html' => $htmlStr, 'width' => $width, 'height' => $height];
  }

}
