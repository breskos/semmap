<?php
/**
 * @author Alexander Bresk (abresk@cip-labs.net)
 * @version 0.0.1
 * @project semmap
**/
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');

# Use this semmap
define('SEMMAP', 'map/semmap.json');

# Here you can choose the output file
define('WEAK_FILE', 'weak_correlations.json');
define('STRONG_FILE', 'strong_correlations.json');

# Here you can change the bound for both correlations
define('STRONG_BOUND', 5);
define('WEAK_BOUND', 20);

require_once 'functions.php';

if (count($argv) != 2){
  show_help($argv);
  exit;
}
$files = get_files($argv[1]);
echo '-> found ' , count($files) , ' file(s)' , PHP_EOL;

# prepare maps
$semmap = load_semmap('semmap.json'); #means 'frame' => array('word1', ..., 'wordN')
$reverse_map = reverse_semmap($semmap); #means 'word' => array('frame1', ..., 'frameN')

$time = start_measure("initialize correlation arrays");
$strong_correlations = $weak_correlations = build_correlations_array($semmap);
stop_measure($time);

foreach($files as $file){
  $contents = implode(' ', file($file));
  replace_tokens($contents, array('.' => '', '!' => '', '?' => '', '#' => ''));
  if(count(explode(' ', $contents)) < 1000){
    # count correlations
    $time = start_measure("count correlations for $file");
    count_correlations($strong_correlations, $weak_correlations, $reverse_map, $contents);
    stop_measure($time);
  }
}

# calculate correlations from counted observations
$time = start_measure("calculate strong correlations");
$strong_correlations = calculate_correlations($strong_correlations);
stop_measure($time);
$time = start_measure("calculate weak correlations");
$weak_correlations = calculate_correlations($weak_correlations);
stop_measure($time);

# write to file
file_put_contents(WEAK_FILE, json_encode($weak_correlations, JSON_PRETTY_PRINT));
file_put_contents(STRONG_FILE, json_encode($strong_correlations, JSON_PRETTY_PRINT));

echo "-> Written to " , WEAK_FILE , " and " , STRONG_FILE , PHP_EOL;

?>
