<?php
// Language
if (!function_exists('__')) {
    function __($key, $default = false, $params = array())
    {
        if (Lang::has($key)) {
            return Lang::get($key, $params);
        } else if ($default !== false) {
            return $default;
        }
        return $key;
    }
}

if (!function_exists('cmsLanguage')) {
    function cmsLanguage($lang = false)
    {
        $languages = Config::get('admin::admin_languages');
        $language = Config::get('admin::admin_language');
        if ($lang) {
            if (isset($languages[$lang])) Session::set('admin_language', $lang);
            else Session::set('admin_language', $language);
        } else {
            if (Session::has('admin_language')) {
                if (!isset($languages[Session::get('admin_language')])) Session::set('admin_language', $language);
            } else {
                Session::set('admin_language', $language);
            }
        }
        App::setLocale(Session::get('admin_language'));
    }
}

cmsLanguage();


if (!function_exists('subMenuItemAttr')) {
    function subMenuItemAttr($nav, $title, $route)
    {
        $nav->add($title, function ($item) use ($route) {
            $item->url = URL::route($route);
            if (URL::current() == URL::route($route)) {
                $item->element('li')->append('class', 'active');
            }
        });
    }
}

if (!function_exists('navigation')) {
    function navigation($menu, $view, $position = null)
    {
        if (!isset($GLOBALS[$menu])) $GLOBALS[$menu] = array();
        if (is_numeric($position)) {
            array_splice($GLOBALS[$menu], $position - 1, 0, array(View::make($view)));
        } else {
            $GLOBALS[$menu][] = View::make($view);
        }

    }
}

if (!function_exists('start')) {
    function start($dir)
    {
        // Include all files from include folder
        if (is_dir($dir)) {
            if ($files = File::allFiles($dir)) {
                foreach ($files as $file) {
                    if (File::extension($file) == 'php') include $file;
                }
            }
        }
    }
}


/*$base_request_path = 'admin/dashboard';
Menu::get('navigation')->add(HTML::link(URL::to($base_request_path), __('admin::admin.dashboard')),
    function ($nav) use ($base_request_path) {
        $class = array();
        if (strpos(trim(Request::path(), '/'), $base_request_path) !== false) $class[] = 'active open';
        //$nav->attribute('li.class', implode(' ', $class));
        //$nav->list->class = implode(' ', $class);
        $nav->label->id = 'li-id';
        $nav->label->class = 'li-class';
    });*/




 
