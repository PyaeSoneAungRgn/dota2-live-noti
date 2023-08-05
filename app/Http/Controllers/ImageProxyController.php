<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageProxyController extends Controller
{
    public function __invoke(Request $request)
    {
        $url = 'https://liquipedia.net/' . $request->input('image');

        $fileContents = file_get_contents($url);

        $fileName = basename($url);

        $contentType = 'image/png';

        return response()->make($fileContents, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
}
