<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function clearCache()
    {
        \Cache::store('file')->flush();
        \Cache::flush();

        return 'ok';
    }

    public function deployAssets()
    {
        chdir($_SERVER['DOCUMENT_ROOT']);
        $rsync = 'rsync -avz --progress';
        $host = 'ninelines_kubkifonbet@kubki-fonbet.linestest.com';
        echo "<pre>";
        echo shell_exec("$rsync $host:assets/ ./assets/ 2>&1");
        echo shell_exec(
            "$rsync --include='*.css' --include='*.js' --include='*.svg' --exclude='*' $host:. ./assets/ 2>&1",
        );
        echo shell_exec("mv -v ./assets/spritemap.svg ./ 2>&1");
        echo "</pre>";
    }
}
