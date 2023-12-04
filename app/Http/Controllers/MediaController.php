<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class MediaController extends Controller
{
    /**
     * Download
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231017 - Created
     */
    public function download(Request $request, Media $media)
    {
        if (!URL::hasValidSignature($request)) {
            return response()->noContent(401);
        }
        return response()->download($media->getPath());
    }
}
