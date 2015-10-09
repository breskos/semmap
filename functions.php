<?php
/**
 * @author Alexander Bresk (abresk@cip-labs.net)
 * @version 0.0.2
 * @project semmap
**/
/**
 * calculates correlations based on the observations
 **/
function calculate_correlations($correlations){
  $calculated_correlations = array();
  $keys = array_keys($correlations);
  for($i = 0, $n = count($keys); $i < $n; $i++){
    $sum = array_sum($correlations[$keys[$i]]);
    foreach($correlations[$keys[$i]] as $key => $value){
      if($sum != 0){
        $calculated_correlations[$keys[$i]][$key] = floatval($correlations[$keys[$i]][$key]) / floatval($sum);
      }else{
        $calculated_correlations[$keys[$i]][$key] = 0;
      }
    }
  }
  return $calculated_correlations;
}


/**
 * counts observations of correlations
 **/
function count_correlations(&$strong_correlations, &$weak_correlations, &$reverse_map, &$contents){
  $tokens = explode(' ', $contents);
  $num_of_tokens = count($tokens);
  # first step, count occurences
  $i = 0;
  foreach($tokens as $token){
    if(array_key_exists($token, $reverse_map)){
      $frames = $reverse_map[$token];
      $start_weak = (($i - WEAK_BOUND < 0)? 0 : ($i - WEAK_BOUND));
      $start_strong = (($i - STRONG_BOUND < 0)? 0 : ($i - STRONG_BOUND));
      $end_weak = (($i + WEAK_BOUND > $num_ok_tokens)? ($num_of_tokens - 1) : ($i + WEAK_BOUND));
      $end_strong = (($i + STRONG_BOUND > $num_ok_tokens)? ($num_of_tokens - 1) : ($i + STRONG_BOUND));
      # weak correlations
      for(; $start_weak <= $end_weak; $start_weak++){
        if(array_key_exists($tokens[$start_weak], $reverse_map)){
          $frames_to = $reverse_map[$tokens[$start_weak]];
          foreach($frames as $frame)
            foreach($frames_to as $f)
              $weak_correlations[$frame][$f]++;
        }
      }

      # strong correlations
      for(; $start_strong <= $end_strong; $start_strong++){
        if(array_key_exists($tokens[$start_strong], $reverse_map)){
          $frames_to = $reverse_map[$tokens[$start_strong]];
          foreach($frames as $frame)
            foreach($frames_to as $f)
              $strong_correlations[$frame][$f]++;
        }
      }
    }
    $i++;
  }
}


/**
 * sets up the corrleation array with all semantic frames as matrix
 **/
function build_correlations_array(&$semmap){
  $array = array();
  foreach($semmap as $key => $value){
    $array[$key] = array();
    foreach($semmap as $k => $v){
      $array[$key][$k] = 0;
    }
  }
  return $array;
}

/**
 * used to replace tokens from the content
 **/
function replace_tokens(&$contents, $mixed){
  foreach($mixed as $k => $v){
    $contents = str_replace($k, $v, $contents);
  }
}

/**
* loads the semmap from json file
**/
function load_semmap($file){
  $content = file($file);
  $content = implode('', $content);
  return json_decode($content);

}

/**
* builds a reverse semmap for a faster lookup
**/
function reverse_semmap(&$semmap){
  $reverse_map = array();
  foreach($semmap as $frame => $parts){
    foreach($parts as $part){
      if(!is_array($reverse_map[$part])){
        $reverse_map[$part] = array();
      }
      $reverse_map[$part][] = $frame;
    }
  }
  return $reverse_map;
}

/**
 * returns an array of files from the given arguments
**/
function get_files($dir){
  if (!is_dir($dir))
    return array($dir);

  $results = array();
  $files = scandir($dir);

  foreach($files as $key => $value){
      $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
      if(!is_dir($path)) {
          $results[] = $path;
      } else if(is_dir($path) && $value != "." && $value != "..") {
          $results[] = get_files($path);
          $results[] = $path;
      }
  }
  return $results;
}

/**
 * mesaures actions of the script
 **/
function start_measure($msg){
  echo "-> " , $msg, " ... ";
  return time();
}

/**
 * stops the mesaurement of the script
 **/
function stop_measure($time){
  echo "[" , (time() - $time) , " sec]" , PHP_EOL;
}

/**
 * shows the help on cmd line
**/
function show_help($arguments){
  echo "semmap " , VERSION , PHP_EOL , PHP_EOL;
  echo "Use this script with one parameter:" , PHP_EOL, PHP_EOL;
  echo "php " , $arguments[0] , " <file or directory>" , PHP_EOL;
}

 ?>
