<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesShowController extends Controller
{
    public function __invoke(Series $series)
    {
        auth()->loginUsingId(1);

        $series->visit()->withIp()->withUser()->withData(['cats' => true]);

        return view('series.show', [
            'series' => $series
        ]);
    }
}
