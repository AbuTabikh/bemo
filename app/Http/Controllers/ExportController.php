<?php

namespace App\Http\Controllers;

use Spatie\DbDumper\Databases\MySql;

class ExportController extends Controller
{
    public function export() {

        $date = date('Ymd_Hms');
        $pathToFile = "dump_{$date}_db.sql";

        MySql::create()->setUserName(config('database.connections.mysql.username'))->setPassword(config('database.connections.mysql.password'))->setDbName(config('database.connections.mysql.database'))->dumpToFile($pathToFile);

        return  response()->download($pathToFile);
    }
}
