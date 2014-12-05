<?php
if (!function_exists('d')) {
    function d($var)
    {
        var_dump($var);
    }
}
if (!function_exists('notempty')) {
    function notempty($var)
    {
        if (!empty($var) || '0' == $var) return true;
        return false;
    }
}

if (!function_exists('file_ext')) {
    function file_ext($contentType, $dot = true)
    {
        if ($dot) $dot = '.';
        else $dot = '';
        $map = array(
                'application/pdf' => $dot . 'pdf',
                'application/zip' => $dot . 'zip',
                'image/gif' => $dot . 'gif',
                'image/jpeg' => $dot . 'jpg',
                'image/png' => $dot . 'png',
                'text/css' => $dot . 'css',
                'text/html' => $dot . 'html',
                'text/javascript' => $dot . 'js',
                'text/plain' => $dot . 'txt',
                'text/xml' => $dot . 'xml',
        );
        if (isset($map[$contentType])) {
            return $map[$contentType];
        }

        // HACKISH CATCH ALL (WHICH IN MY CASE IS
        // PREFERRED OVER THROWING AN EXCEPTION)
        $pieces = explode('/', $contentType);
        return '.' . array_pop($pieces);
    }
}

if (!function_exists('read_header')) {
    function read_header($ch, $header)
    {
        // read headers
        echo "Read header: ", $header;
        return strlen($header);;
    }
}

if (!function_exists('get_domain')) {
    function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }
}

if (!function_exists('nf')) {
    function nf($number)
    {
        return number_format($number, 2);
    }
}

if (!function_exists('nf')) {
    function nf($number)
    {
        return number_format($number, 2);
    }
}

if (!function_exists('assets_admin')) {
    function assets_admin($url = null)
    {
        return url('packages/module/admin/assets/' . $url);
    }
}

if (!function_exists('assets_frontend')) {
    function assets_frontend($url = null)
    {
        return url('packages/module/frontend/assets/' . $url);
    }
}

if (!function_exists('get_class_name')) {
    function get_class_name($obj, $encode = true)
    {
        $name = get_class($obj);
        if ($encode)
            return urlencode2($name);
        return $name;
    }
}

if (!function_exists('create_dynamic_attributes')) {
    function create_dynamic_attributes($class, $model, $id, $view = null)
    {
        $attributes = array(
                'class' => $class,
                'data-main-model' => get_class_name($model),
                'data-id' => $id,
                'data-view' => $view ? urlencode2($view) : null,
        );

        return HTML::attributes($attributes);
    }
}

if (!function_exists('array_pusher')) {
    function array_pusher()
    {
        $args = func_get_args();
        $array = $args[0];
        foreach ($args as $key => $arg) {
            if ($key == 0) continue;
            array_push($array, $arg);
        }
        return $array;
    }
}

if (!function_exists('urlencode2')) {
    function urlencode2($str)
    {
        return urlencode(urlencode($str));
    }
}

if (!function_exists('urldecode2')) {
    function urldecode2($str)
    {
        return urldecode(urldecode($str));
    }
}

if (!function_exists('propertyToArray')) {
    function propertyToArray($array, $property)
    {
        $out = array();
        foreach ($array as $item) {
            if (isset($item[$property])) {
                $out[] = $item[$property];
            }
        }
        return $out;
    }
}

if (!function_exists('admin_asset')) {
    function admin_asset($asset)
    {
        return url('packages/module/admin/assets/' . $asset);
    }
}

if (!function_exists('frontend_asset')) {
    function frontend_asset($asset)
    {
        return url('packages/module/frontend/assets/' . $asset);
    }
}

if (!function_exists('module_asset')) {
    function module_asset($module, $asset)
    {
        return url('packages/module/' . $module . '/assets/' . $asset);
    }
}

if (!function_exists('generatePageTree')) {
    function generatePageTree($datas, $parent = 0, $depth = 0)
    {
        //if ($depth > 1000) return ''; // Make sure not to have an endless recursion
        $tree = '<ul>';
        foreach($datas as $key => $item){
       // for ($i = 0, $ni = count($datas); $i < $ni; $i++) {
            //if ($datas[$i]['parent'] == $parent) {
            if($item->parent_id == $parent){
                $tree .= '<li>';
        $tree .= $item->group_name;
        $tree .= generatePageTree($datas, $item->group_id, $depth + 1);
                //$tree .= $datas[$i]['name'];
                //$tree .= generatePageTree($datas, $datas[$i]['id'], $depth + 1);
                $tree .= '</li>';
            }
        }
        $tree .= '</ul>';
        return $tree;
    }
}




 