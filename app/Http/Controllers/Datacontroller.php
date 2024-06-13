<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class DataController extends Controller
{
    public function index()
    {
        $response = Http::get('https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json');
        

        if ($response->successful()) {
            $data = $response->json();
            $items = $data['channel']['item'];
            return view('datapage', ['data' => $items]);
        } else {
            return view('datapage', ['data' => []]);
        }
    }

    public function inde()
    {
        $response = Http::get('https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json');
        

        if ($response->successful()) {
            $data = $response->json();
            $items = $data['channel']['item'];
            return view('datapagetable', ['data' => $items]);
        } else {
            return view('datapagetable', ['data' => []]);
        }
    }
}
