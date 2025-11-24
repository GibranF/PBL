<?php

if (! function_exists('loadImage')) {
    function loadImage($item) {
        return $item->gambar
            ? asset('storage/' . $item->gambar)
            : asset('images/layanan/defaultlayanan.png');
    }
}
