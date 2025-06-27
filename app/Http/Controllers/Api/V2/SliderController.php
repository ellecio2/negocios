<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\SliderCollection;
use Cache;

class SliderController extends Controller
{
    public function sliders()
    {
        $slider_data = [];
        $images = get_setting('home_slider_images') != null ? json_decode(get_setting('home_slider_images'), true) : [];
        $texts1 = get_setting('home_slider_text1') != null ? json_decode(get_setting('home_slider_text1'), true) : [];
        $texts2 = get_setting('home_slider_text2') != null ? json_decode(get_setting('home_slider_text2'), true) : [];
        $texts3 = get_setting('home_slider_text3') != null ? json_decode(get_setting('home_slider_text3'), true) : [];
        $texts4 = get_setting('home_slider_text4') != null ? json_decode(get_setting('home_slider_text4'), true) : [];

        foreach($images as $key => $image) {
            $slider_data[] = [
                'image' => $image,
                'text1' => $texts1[$key] ?? '',
                'text2' => $texts2[$key] ?? '',
                'text3' => $texts3[$key] ?? '',
                'text4' => $texts4[$key] ?? ''
            ];
        }

        return new SliderCollection($slider_data);
    }

    public function bannerOne()
    {
        $banner_data = [];
        $images = get_setting('home_banner1_images') != null ? json_decode(get_setting('home_banner1_images'), true) : [];
        $texts1 = get_setting('home_banner1_text1') != null ? json_decode(get_setting('home_banner1_text1'), true) : [];
        $texts2 = get_setting('home_banner1_text2') != null ? json_decode(get_setting('home_banner1_text2'), true) : [];
        $texts3 = get_setting('home_banner1_text3') != null ? json_decode(get_setting('home_banner1_text3'), true) : [];

        foreach($images as $key => $image) {
            $banner_data[] = [
                'image' => $image,
                'text1' => $texts1[$key] ?? '',
                'text2' => $texts2[$key] ?? '',
                'text3' => $texts3[$key] ?? ''
            ];
        }

        return new SliderCollection($banner_data);
    }

    public function bannerTwo()
    {
        $banner_data = [];
        $images = get_setting('home_banner2_images') != null ? json_decode(get_setting('home_banner2_images'), true) : [];
        $texts1 = get_setting('home_banner2_text1') != null ? json_decode(get_setting('home_banner2_text1'), true) : [];
        $texts2 = get_setting('home_banner2_text2') != null ? json_decode(get_setting('home_banner2_text2'), true) : [];

        foreach($images as $key => $image) {
            $banner_data[] = [
                'image' => $image,
                'text1' => $texts1[$key] ?? '',
                'text2' => $texts2[$key] ?? ''
            ];
        }

        return new SliderCollection($banner_data);
    }

    public function bannerThree()
    {
        $banner_data = [];
        $images = get_setting('home_banner3_images') != null ? json_decode(get_setting('home_banner3_images'), true) : [];
        $texts1 = get_setting('home_banner3_text1') != null ? json_decode(get_setting('home_banner3_text1'), true) : [];
        $texts2 = get_setting('home_banner3_text2') != null ? json_decode(get_setting('home_banner3_text2'), true) : [];

        foreach($images as $key => $image) {
            $banner_data[] = [
                'image' => $image,
                'text1' => $texts1[$key] ?? '',
                'text2' => $texts2[$key] ?? ''
            ];
        }

        return new SliderCollection($banner_data);
    }

    public function bannerFour()
    {
        $banner_data = [];
        $images = get_setting('home_banner4_images') != null ? json_decode(get_setting('home_banner4_images'), true) : [];
        $texts1 = get_setting('home_banner4_text1') != null ? json_decode(get_setting('home_banner4_text1'), true) : [];
        $texts2 = get_setting('home_banner4_text2') != null ? json_decode(get_setting('home_banner4_text2'), true) : [];
        $texts3 = get_setting('home_banner4_text3') != null ? json_decode(get_setting('home_banner4_text3'), true) : [];

        foreach($images as $key => $image) {
            $banner_data[] = [
                'image' => $image,
                'text1' => $texts1[$key] ?? '',
                'text2' => $texts2[$key] ?? '',
                'text3' => $texts3[$key] ?? ''
            ];
        }

        return new SliderCollection($banner_data);
    }

    public function bannerFive()
    {
        $banner_data = [];
        $images = get_setting('home_banner5_images') != null ? json_decode(get_setting('home_banner5_images'), true) : [];
        $texts1 = get_setting('home_banner5_text1') != null ? json_decode(get_setting('home_banner5_text1'), true) : [];
        $texts2 = get_setting('home_banner5_text2') != null ? json_decode(get_setting('home_banner5_text2'), true) : [];
        $texts3 = get_setting('home_banner5_text3') != null ? json_decode(get_setting('home_banner5_text3'), true) : [];

        foreach($images as $key => $image) {
            $banner_data[] = [
                'image' => $image,
                'text1' => $texts1[$key] ?? '',
                'text2' => $texts2[$key] ?? '',
                'text3' => $texts3[$key] ?? ''
            ];
        }

        return new SliderCollection($banner_data);
    }
}