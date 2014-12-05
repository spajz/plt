<?php
if (!function_exists('cena')) {
    function cena($cena, $kurs = true, $pdv = null, $format = true)
    {
        $product = App\Modules\Product\Models\Product::find(3132);
        $value = $product->cena_prodajna;
        if ($kurs) $cena = $cena * $value;
        if (is_numeric($pdv)) $cena = $cena * ($pdv + 100) / 100;
        $cena = round($cena, 0);
        if ($format) return number_format($cena, 2);
        return $cena;
    }
}