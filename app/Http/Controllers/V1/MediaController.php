<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class MediaController extends Controller
{
    /**
     * Get Download URL
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231017 - Created
     */
    public function getUrl(Media $media)
    {
        $downloadURL = URL::temporarySignedRoute('media.download', now()->addMinutes(60), ['media' => $media->id]);

        return response()->json(['data' => ['download_url' => $downloadURL]]);
    }
}
