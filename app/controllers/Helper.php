<?php
class Helper
{
    public static function truncate($str = false, $length = false, $end_string = '...')
    {
        $str = strip_tags($str);
        $str = str_replace(array("\r", "\r\n", "\n"), '', $str);
        if (strlen($str) < $length) return $str;
        $position = strrpos(substr($str, 0, $length), ' ');
        return substr($str, 0, $position) . $end_string;
    }

    public static function truncate_decode($str = false, $length = false, $end_string = '...')
    {
        $str = trim(strip_tags(html_entity_decode($str, ENT_COMPAT, 'utf-8')));
        $str = str_replace(array("\r", "\r\n", "\n"), '', $str);
        if (strlen($str) < $length) return $str;
        $position = strrpos(substr($str, 0, $length), ' ');
        return substr($str, 0, $position) . $end_string;
    }

    public static function numbers_array($from = false, $to = false, $order = 'asc', $leading_zeros = 0)
    {
        $numbers = array();
        for ($i = $from; $i <= $to; $i++) {
            if ($leading_zeros > 0) $numbers[sprintf("%0" . $leading_zeros . "d", $i)] = sprintf("%0" . $leading_zeros . "d", $i);
            else $numbers[$i] = $i;
        }

        if ($order == 'desc') arsort($numbers);
        return $numbers;
    }

    public static function sort_array_by_array($array, $order_array)
    {
        $out = array();
        foreach ($order_array as $key) {
            if (isset($array[$key])) {
                $out[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return $out + $array;
    }

    public static function array_to_list($array, $parent = 0)
    {
        $r = '';
        foreach ($array as $item) {
            if ($item->parent_id == $parent) {
                $r = $r . "<li>" . $item->title . self::array_to_list($array, $item->id) . "</li>";
            }
        }
        return ($r == '' ? '' : "<ul>" . $r . "</ul>");
    }

    /**
     * Include files from dir.
     */
    public static function includeAllFiles($dir = '')
    {
        $files = new FilesystemIterator($dir);

        foreach ($files as $file) {
            if ($file->isFile()) {
                require $file->getPathname();
            }
        }
    }

    public static function jsScript($code = false, $script_tag = true, $jq_function ='ready')
    {
        $out = '';
        if($script_tag) $out .= '<script type="text/javascript">' . "\n";
        if($jq_function == 'ready') $out .= '$(document).ready(function () {'. "\n";
        if($jq_function == 'load') $out .= '$(window).load(function () {'. "\n";
        $out .= $code;
        $out .= '});';
        if($script_tag) $out .= '</script>'. "\n";
        return $out;
    }
}






