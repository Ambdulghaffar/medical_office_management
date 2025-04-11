<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Paramètres de filtrage (mois, année et jour)
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $day = $request->input('day', Carbon::today()->day); // Par défaut, on utilise le jour actuel
        
        // Récupérer les rendez-vous pour le mois et le jour
        $appointmentsQuery = Appointment::where('availability', 'free')
                                         ->whereMonth('date_appointment', $month)
                                         ->whereYear('date_appointment', $year)
                                         ->whereDay('date_appointment', $day);
    
        // Ajouter un tri sur l'heure de début
        $appointments = $appointmentsQuery->orderBy('start_time')->get(); // Tri par heure de début
    
        // Calculer les jours du mois
        $currentMonth = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $currentMonth->daysInMonth;
        $daysOfMonth = collect(range(1, $daysInMonth)); // Tableau des jours du mois
    
        // Navigation vers le mois précédent et suivant
        $previousMonth = $currentMonth->copy()->subMonth()->month;
        $previousYear = $currentMonth->copy()->subMonth()->year;
        $nextMonth = $currentMonth->copy()->addMonth()->month;
        $nextYear = $currentMonth->copy()->addMonth()->year;
    
        return view('dashboard.appointment.list_appointment', compact('appointments', 'currentMonth', 'daysOfMonth', 'previousMonth', 'previousYear', 'nextMonth', 'nextYear', 'day'));
    }
    
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('dashboard.appointment.create_appointment');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_appointment' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time', // L'heure de fin doit être après l'heure de début
        ]);

        Appointment::create([
            'date_appointment' => $request->date_appointment,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('appointment')->with('success', 'Le créneau a été créé  avec succès');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


     public function show(Request $request)
     {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $day = $request->input('day', Carbon::today()->day);
 

         // Récupérer les rendez-vous pour le mois
         $appointmentsQuery = Appointment::where('availability', 'free')
                                          ->whereMonth('date_appointment', $month)
                                          ->whereYear('date_appointment', $year);
     
         // Si un jour spécifique est sélectionné, filtrer les rendez-vous pour ce jour
         if ($day) {
             $appointmentsQuery->whereDay('date_appointment', $day);
         }
     
         // Ajouter un tri sur l'heure de début
         $appointments = $appointmentsQuery->orderBy('start_time')->get(); // Trie par heure de début
     
         // Calculer les jours du mois
         $currentMonth = Carbon::createFromDate($year, $month, 1);
         $daysInMonth = $currentMonth->daysInMonth;
         $daysOfMonth = collect(range(1, $daysInMonth)); // Tableau des jours du mois
     
         // Navigation vers le mois précédent et suivant
         $previousMonth = $currentMonth->copy()->subMonth()->month;
         $previousYear = $currentMonth->copy()->subMonth()->year;
         $nextMonth = $currentMonth->copy()->addMonth()->month;
         $nextYear = $currentMonth->copy()->addMonth()->year;
     
         return view('dashboard.appointment.take_appointment', compact('appointments', 'currentMonth', 'daysOfMonth', 'previousMonth', 'previousYear', 'nextMonth', 'nextYear', 'day'));
     }
     
     
     
     



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('dashboard.appointment.edit_appointment', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Validation des données
        $request->validate([
            'date_appointment' => 'required|date',
            'start_time' => 'required|',
            'end_time' => 'required|after:start_time',
            'availability' => 'required|in:free,reserved,unavailable',
        ]);

        $appointment->fill($request->all())->save();

        // Mise à jour du rendez-vous
        /*       $appointment->update([
            'date_appointment' => $request->date_appointment,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'availability' => $request->availability,
        ]); */

        // Redirection avec un message de succès
        return redirect()->route('appointment')->with('success', 'Le créneau a été mis à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->back()->with('success', 'Le créneau a été supprimé avec succès !');
    }
}
