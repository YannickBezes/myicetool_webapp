<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Releve;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\CascadeController;

class DetailCascadeController extends Controller {
    public function afficherDetails($cascade_id) {
        $infosCascade = app()->make(CascadeController::class)->callAction('getCascadeDetails', [$cascade_id]);

        if(count($infosCascade->zones) !== 0) {
            $zone_id = $infosCascade->zones[0]->id;
            $releves = Releve::where('zone_id', $zone_id)->orderBy('date', 'asc')->get();
            $relevesDate = array();
            $releveTemps = array();
            foreach ($releves as $releve) {
                $releveTemps[] = $releve->temperature_moyenne;
                $toto = $releve->date;
                setlocale(LC_TIME, "fr_FR");
                $relevesDate[] = strftime('%Hh%M', strtotime($toto));
            }
    
            $chartjs = app()->chartjs
            ->name('lineChartTest')
            ->type('line')
            ->size(['width' => 200, 'height' => 100])
            ->labels($relevesDate)
            ->datasets([
                [
                    "label" => "températures (en °C)",
                    'backgroundColor' => "rgba(133, 209, 227, 0.31)",
                    'borderColor' => "rgba(133, 209, 227, 0.7)",
                    "pointBorderColor" => "rgba(133, 209, 227, 0.7)",
                    "pointBackgroundColor" => "rgba(133, 209, 227, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    'data' => $releveTemps,
                ]
            ])
            ->options([]);
            return view('detailCascade', [ 'infosCascade' => $infosCascade, 'chartjs' => $chartjs]);
        }
        return view('detailCascade', [ 'infosCascade' => $infosCascade ]);
    }
}
