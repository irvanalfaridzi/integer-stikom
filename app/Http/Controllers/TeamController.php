<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Team;
use App\Models\Participants;
use App\User;
use App\Models\Submission_team;
use App\Models\Submission;
use Illuminate\Support\Facades\Session;
use Dotenv\Regex\Success;

class TeamController extends Controller
{
    public function index(){
        $teams = Auth::user()->username;
        // dd($teams);
        $team_ahh = Team::join('competitions', 'teams.competition_id', '=', 'competitions.id')->where('username', $teams)->get();
        $participants = Participants::where('team_id' , $teams )->orderBy('id', 'asc')->get();
        return view('team.dashboard-peserta',['participants' => $participants],['teamku' => $team_ahh]);
    }

    public function update($id, Request $request){

        $participants = Participants::find($id);
        $participants->identity_card = $request->identity_card;
        $participants->name = $request->name;
        $participants->birth_date = $request->birth_date;
        $participants->email = $request->email;
        $participants->phone = $request->phone;
        $participants->save();
        return redirect('team');

        // $participants->update($request->all());
        // return redirect('team')->with('succes','sukses bro'); 

        // return $request;
    }

    public function payments(){
        return view('team.payments');
    }

    public function submission(){
        return view('team.submission');
    }

    public function video(){
        $team_id = Auth::user()->team_id;
        $competition_id = Team::select('competition_id')->where('id', $team_id)->first()->competition_id;

        $submission_id = Submission::select('id')->where('name', 'Pengumpulan Link Video')
                                                 ->where('competition_id', $competition_id)
                                                 ->first()->id;
        $submission_teams = Submission_team::where('team_id', Auth::user()->team_id)
                                            ->where('submission_id', $submission_id);

        $submission_team = $submission_teams->get();

        $done = $submission_teams->first();
                    
        
        return view('team.video',[
            'submission_team' => $submission_team,
            'done' => $done,
        ]);
    }

    public function videostore(Request $request){

        $team_id = Auth::user()->team_id;
        $competition_id = Team::select('competition_id')->where('id', $team_id)->first()->competition_id;

        $document = $request->document;
        $submission_id = Submission::select('id')->where('name', 'Pengumpulan Link Video')
                                                 ->where('competition_id', $competition_id)
                                                 ->first()->id;

        Submission_team::create([
            'submission_id' => $submission_id,
            'team_id'       => $team_id,
            'document'      => $document,
        ]);

        return redirect('team/video')->with('success', 'Data telah terkirim');
    }

    public function setting(){
        return view('team.setting');
    }


    public function settingstore(Request $request){

        $pass = User::select('password')->where('team_id', Auth::user()->team_id)->first()->password;

        if (Hash::check($request->password_lama, $pass)) {
            
            User::where('team_id', Auth::user()->team_id)->update(array('password' => Hash::make($request->password)));
            Team::where('id', Auth::user()->team_id)->update(array('password' => Hash::make($request->password)));
            Session::flash('sukses','Selamat password anda telah diubah');
            
        }else{
            Session::flash('gagal','Maaf password yang anda masukan salah');
        }

        return redirect('team/setting')->with('success', 'Data telah terkirim');
    }

}
