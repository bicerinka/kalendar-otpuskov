<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {

        if($request->ajax()) {

            $data = Event::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end', 'user_id', 'approved']);

            return response()->json($data);
        }

        return view('fullcalender');
    }

    public function ajax(Request $request)
    {
        if (!auth()->user()->id) {
            return redirect()->route('login');
        }


        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'title' => $request->title . (auth()->user()->id == 1 ?  '. Подтвеждено!' : ''),
                    'start' => $request->start,
                    'end' => $request->end,
                    'approved' => auth()->user()->id == 1 ? 1 : 0,
                    'user_id' => auth()->user()->id
                ]);

                return response()->json($event);
                break;

            case 'update':
                if (auth()->user()->id == $request->user_id){
                    $event = Event::find($request->id)->update([
                        'title' => $request->title,
                        'start' => $request->start,
                        'end' => $request->end,
                    ]);

                    return response()->json($event);
                }
                break;

            case 'delete':
                $event = Event::find($request->id)->delete();

                return response()->json($event);
                break;
            case 'approve':
                if (auth()->user()->id == 1){
                    $event = Event::find($request->id)->update([
                        'approved' => 1,
                        'title' => $request->title . '. Подтвеждено!',
                    ]);

                    return response()->json($event);
                }
                break;

            default:
                # code...
                break;
        }
    }
}