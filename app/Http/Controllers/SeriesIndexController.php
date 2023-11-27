<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesIndexController extends Controller
{
    public function __invoke()
    {
        return view('series.index', [
            'popular' => Series::withTotalVisitCount()->popularThisYear()->get()
        ]);
    }
}
