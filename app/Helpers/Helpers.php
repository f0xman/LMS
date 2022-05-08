<?php
/**
 * String helper. Word endings for numbers.
 *
 * @param $n
 * @param $titles
 *
 * @return string
 * echo ending(631, array('яблоко', 'яблока', 'яблок'));
 */
function ending($n, $titles) {
  $cases = array(2, 0, 1, 1, 1, 2);
  return $n.' '.$titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

