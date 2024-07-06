<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Fermeture;
use App\Models\Appartement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;


class FermetureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Appartement $appartement)
    {

        $appartement_id = $appartement->id;

        $intervalle = Reservation::where("appartement_id", $appartement_id)
            ->select("start_time","end_time")
            ->get();


        $fermetures = Fermeture::where('appartement_id', $appartement->id)->get();
        return view('fermetures.index', ['fermetures' => $fermetures,
                                                'appartement' => $appartement,
                                                'intervalles' => $intervalle]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($appartementId)
    {
        $appartement = Appartement::findOrFail($appartementId);

        $intervalles = Reservation::where("appartement_id", $appartement->id)
            ->select("start_time","end_time")
            ->get();

        $fermetures = Fermeture::where("appartement_id", $appartement->id)
            ->select("start","end")
            ->get();

        $dateInBase = [];

        foreach($fermetures as $fermeture) {
            $dateInBase[] = [
                'from' => date("d-m-Y", strtotime($fermeture->start)),
                'to' => date("d-m-Y", strtotime($fermeture->start))
            ];
        }


        foreach($intervalles as $intervalle) {
            $dateInBase[] = [
                'from' => date("d-m-Y", strtotime($intervalle->start_time)),
                'to' => date("d-m-Y", strtotime($intervalle->end_time))
            ];
        }

        return view('fermetures.create', ['appartement'=>$appartement,
                                        'intervalles'=>$intervalles,
                                        'fermetures'=>$fermetures, 
                                    'datesInBase' => $dateInBase]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $appartement)
    {

        $validatedData = $request->validate([
            'comment' => ['required', 'string', 'max:140'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        $validatedData['start_time'] = date('Y-m-d', strtotime($validatedData['start_time']));
        $validatedData['end_time'] = date('Y-m-d', strtotime($validatedData['end_time']));

        $fermeture = new Fermeture();
        $fermeture->comment = $validatedData['comment'];
        $fermeture->start = $validatedData['start_time'];
        $fermeture->end = $validatedData['end_time'];
        $fermeture->appartement_id = $appartement;
        $fermeture->save();

        $appartement = Appartement::findOrFail($appartement);

        return redirect()->route('fermeture.index', ['appartement' => $appartement])->with('success', "Réservation bien prise en compte");
    }

    public function storeRecurring(Request $request, Appartement $appartement)
    {
        $validatedData = $request->validate([
            'days' => ['required', 'array'],
            'days.*' => ['integer', 'between:0,7'],
        ]);

        

        $appartement->recurringClosures = $validatedData['days'];
        $appartement->save();

        return redirect()->route('fermeture.index', ['appartement' => $appartement])
                         ->with('success', "Fermeture récurrente bien prise en compte");
    }

    public function createRecurringClosure(Request $request, Appartement $appartement)
    {
        $validatedData = $request->validate([
            'days' => ['required', 'array'],
            'month_start' => ['required', 'date'],
            'month_end' => ['required', 'date'],
        ]);

        $validatedData['month_end'] = date('Y-m-d', strtotime($validatedData['month_end']. ' +1 day'));
    
        $startDate = Carbon::parse($validatedData['month_start']);
        $endDate = Carbon::parse($validatedData['month_end']);
        $days = $validatedData['days'];
    
        $week = [
            0 => Carbon::SUNDAY,
            1 => Carbon::MONDAY,
            2 => Carbon::TUESDAY,
            3 => Carbon::WEDNESDAY,
            4 => Carbon::THURSDAY,
            5 => Carbon::FRIDAY,
            6 => Carbon::SATURDAY
        ];
    
        while ($startDate->lte($endDate)) {
            foreach ($days as $day) {
                $closureDate = $startDate->copy()->next($week[$day]);
    
                if ($closureDate->gt($endDate)) {
                    continue;
                }
    
                $closureEvent = Fermeture::where('appartement_id', $appartement->id)
                    ->whereDate('start', $closureDate)
                    ->whereDate('end', $closureDate)
                    ->first();
    
                $reservationEvent = Reservation::where('appartement_id', $appartement->id)
                    ->where(function($query) use ($closureDate) {
                        $query->whereDate('start_time', '<=', $closureDate)
                              ->whereDate('end_time', '>=', $closureDate);
                    })
                    ->first();
    
                if (!$closureEvent && !$reservationEvent) {
                    Fermeture::create([
                        'comment' => 'Fermeture récurrente',
                        'start' => $closureDate->format('Y-m-d'),
                        'end' => $closureDate->format('Y-m-d'),
                        'appartement_id' => $appartement->id,
                    ]);
                }
            }
    
            $startDate->addWeek();
        }
    
        return redirect()->route('fermeture.index', ['appartement' => $appartement])
            ->with('success', "Fermeture récurrente bien prise en compte");
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Fermeture $fermeture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fermeture $fermeture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $appartement, $fermeture)
    {

        $fermetures = Fermeture::findOrFail($fermeture);

        $validator = Validator::make($request->all(), [
            'start_time' => ['required', 'date', 'after_or_equal:today'],
            'end_time' => ['required', 'date', 'after:start'],
        ]);

        $validatedData = $validator->validated();

        $fermetures->start_time = $validatedData['start'];
        $fermetures->end_time = $validatedData['end'];
        $fermetures->save();

        return redirect()->route('fermeture.index', $appartement)
            ->with('success', 'Reservation deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($appartement, $fermeture)
    {
        $fermetures = Fermeture::findOrFail($fermeture);

        $fermetures->delete();

        return redirect()->route('fermeture.index', $appartement)
            ->with('success', 'Reservation deleted successfully');
    }


    public function generateRecurring(Appartement $appartement)
    {
        $recurringClosures = $appartement->getRecurringClosures();
        $today = Carbon::today();
        $endDate = $today->copy()->addWeeks(4);

        $daysMap = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        while ($today->lte($endDate)) {
            foreach ($recurringClosures as $dayOfWeek) {
                if (!array_key_exists($dayOfWeek, $daysMap)) {
                    continue;
                }

                $closureDate = $today->copy()->next($daysMap[$dayOfWeek]);

                $closureEvent = Fermeture::where('appartement_id', $appartement->id)
                    ->whereDate('start', $closureDate)
                    ->whereDate('end', $closureDate)
                    ->first();

                $reservationEvent = Reservation::where('appartement_id', $appartement->id)
                    ->where(function($query) use ($closureDate) {
                        $query->whereDate('start_time', '<=', $closureDate)
                              ->whereDate('end_time', '>=', $closureDate);
                    })
                    ->first();
                
                if (!$closureEvent && !$reservationEvent) {
                    Fermeture::create([
                        'comment' => 'Fermeture récurrente',
                        'start' => $closureDate->format('Y-m-d'),
                        'end' => $closureDate->format('Y-m-d'),
                        'appartement_id' => $appartement->id,
                    ]);
                }
            }

            $today->addWeek();
        }
    }
}
