<?php
// Copyright 2011-present Element 34
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

include_once('WebDriverExceptions.php');

function unwind_associated_array($arr) {
    if (count($arr) > 0) {        
        $u = "array(";
        foreach ($arr as $k => $v) {
            $u = $u . $k . " => " . $v . ",";
        }
        $u = $u . ")";
        return $u;
    } else {
        return "array()";
    }
}

class PHPWebDriver_WebDriverActionChains {
  protected $session;
  protected $actions = array();

  public function __construct($session) {
    $this->session = $session;
    return $this;
  }
  
  public function perform() {
    foreach ($this->actions as $action) {
      // var_dump($action);
      $before = error_get_last();
      $result = eval($action);
      $after = error_get_last();
      if ($after !== $before) {
        throw new PHPWebDriver_ChainError($after['message']);
      }
    }
  }
  
  public function click($on_element = null, $curl_opts=array()) {
    if ($on_element) {
      $this->moveToElement($on_element, $curl_opts);
    }
    $this->actions[] = '$this->session->click(' . unwind_associated_array($curl_opts) . ');';
    return $this;
  }

  public function clickAndHold($on_element = null, $curl_opts = array()) {
    if ($on_element) {
      $this->moveToElement($on_element, $curl_opts);
    }
    $this->actions[] = '$this->session->buttondown(' . unwind_associated_array($curl_opts) . ');';
    return $this;
  }
    
  public function contextClick($on_element = null, $curl_opts = array()) {
    if ($on_element) {
      $this->moveToElement($on_element, $curl_opts);
    }
    $this->actions[] = '$this->session->click(array("button" => 2), ' . unwind_associated_array($curl_opts) . ');';
    return $this;
  }
    
  public function doubleClick($on_element=null, $curl_opts=array()) {
    if ($on_element) {
      $this->moveToElement($on_element, $curl_opts);
    }
    $this->actions[] = '$this->session->doubleclick(' . unwind_associated_array($curl_opts) . ');';
    return $this;
  }
    
  public function dragAndDrop($source, $target, $curl_opts = array()) {
    $this->moveToElement($source, $curl_opts);
    $this->clickAndHold($source, $curl_opts);
    $this->moveToElement($target, $curl_opts);
    $this->release($target);
    return $this;
  }
    
  public function dragAndDropByOffset($source, $target, $xOffset, $yOffset) {}
    
  public function keyDown($value, $onElement = null) {}
    
  public function keyUp($value, $onElement = null) {}

  public function moveByOffset($source, $xOffset, $yOffset) {}
        
  public function moveToElement($toElement, $curl_opts = array()) {
    $this->actions[] = '$this->session->moveto(array("element" => "' . $toElement->getID() . '"), ' . unwind_associated_array($curl_opts) . ');';
    return $this;
  }  

  public function moveToElementWithOffset($toElement, $xOffset, $yOffset) {
      $this->actions[] = '$this->session->moveto(array("element" => "' . $toElement->getID() . '", "xoffset" => '.$xOffset.', "yoffset" => '.$yOffset.'));';
      return $this;
  }

  public function release($on_element = null, $curl_opts = array()) {
    if ($on_element) {
      $this->moveToElement($on_element, $curl_opts);
    }
    $this->actions[] = '$this->session->buttonup(' . unwind_associated_array($curl_opts) . ');';
    return $this;
  }
    
  public function sendKeys($keysToSend) {}
    
  public function sendKeysToElement($toElement, $keysToSend) {}
}

?>