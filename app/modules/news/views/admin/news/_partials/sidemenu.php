<?php
$base = 'admin.news.';
$class = '';
if (Request::segment(2) == 'news') $class = ' class="active open"';
$route = Route::currentRouteName();
?>
<li<?php echo $class; ?>>
    <a href="#" class="dropdown-toggle">
        <i class="icon-list"></i>
        <span class="menu-text">News</span>
        <b class="arrow icon-angle-down"></b>
    </a>

    <ul class="submenu">

        <li<?php if ($route == $base . 'index') echo ' class="active"'; ?>>
            <a href="<?php echo route($base . 'index'); ?>">
                <i class="icon-double-angle-right"></i>
                List
            </a>
        </li>

        <li<?php if ($route == $base . 'create') echo ' class="active"'; ?>>
            <a href="<?php echo route($base . 'create'); ?>">
                <i class="icon-double-angle-right"></i>
                Create
            </a>
        </li>
    </ul>
</li>