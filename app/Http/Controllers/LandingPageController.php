<?php

namespace App\Http\Controllers;

use App\Models\Competitions;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Participants;
use App\User;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competitions = Competitions::all();
        return view('welcome', ['competitions' => $competitions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { }

    public function registerteam(Request $request)
    {
        $name = $request->get('name');
        $competition_id = $request->get('competition_id');
        $username = $request->get('username');
        $password = bcrypt($request->get('password'));
        $institution = $request->get('institution');
        if (Team::where('username', '=', $username)->exists()) {
            return redirect('/#pendaftaran')->withErrors(['Nama telah digunakan']);
        }

        return view('register-team', [
            'name' => $name,
            'username' => $username,
            'competition_id' => $competition_id,
            'password' => $password,
            'institution' => $institution
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'name_1'  => 'required',
            'email_1' => 'required|email',
            'phone_1' => 'required',
            'identity_card_1' => 'required|image|mimes:jpeg,png,jpg',
            'name_2'  => 'required',
            'email_2' => 'required|email',
            'phone_2' => 'required',
            'identity_card_2' => 'required|image|mimes:jpeg,png,jpg',
            'identity_card_3' => 'image|mimes:jpeg,png,jpg'
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator->errors());
        } else {
            $name_1 = $request->get('name_1');
            $name_2 = $request->get('name_2');
            $name_3 = $request->get('name_3');
            $birth_date_1 = $request->get('birth_date_1');
            $birth_date_2 = $request->get('birth_date_2');
            $birth_date_3 = $request->get('birth_date_3');
            $email_1 = $request->get('email_1');
            $email_2 = $request->get('email_2');
            $email_3 = $request->get('email_3');
            $phone_1 = $request->get('phone_1');
            $phone_2 = $request->get('phone_2');
            $phone_3 = $request->get('phone_3');
            $identity_card_1 = $request->file('identity_card_1');
            $identity_card_2 = $request->file('identity_card_2');
            $identity_card_3 = $request->file('identity_card_3');
            $status_1 = "Ketua";
            $status_2 = "Anggota";
            $status_3 = "Anggota";
            
            $no = 2;
            if ($name_3) {
                $no = 3;
            }
            
            $name_team = $request->get('name_team');
            $username = $request->get('username');
            $password = $request->get('password');
            $institution = $request->get('institution');
            $competition_id = $request->get('competition_id');

            $identity_card_1_name =  $username . '_' . $name_1 . '.' . $identity_card_1->getClientOriginalExtension();
            $identity_card_2_name =  $username . '_' . $name_2 . '.' . $identity_card_2->getClientOriginalExtension();

            if ($identity_card_3 != null) {
                $identity_card_3_name =  $username . '_' . $name_3 . '.' . $identity_card_3->getClientOriginalExtension();
                $identity_card_3->move('uploads/identity_card/' , $identity_card_3_name);
            } else {
                $identity_card_3_name = null;
            }

            $identity_card_1->move('uploads/identity_card/' , $identity_card_1_name);
            $identity_card_2->move('uploads/identity_card/' , $identity_card_2_name);

            $team = new Team;
            $team->id = $username;
            $team->name = $name_team;
            $team->username = $username;
            $team->password = $password;
            $team->institution = $institution;
            $team->status = "Belum Terverifikasi";
            $team->competition_id = $competition_id;
            $team->save();

            

            for ($i = 1; $i <= $no; $i++) {
                $participant = new Participants;
                $team_id = $username;
                $participant->team_id = $team_id;
                $name = 'name_' . $i;
                $birth_date = 'birth_date_' . $i;
                $email = 'email_' . $i;
                $phone = 'phone_' . $i;
                $status = 'status_' . $i;
                $identity_card = 'identity_card_' . $i . '_name';
                $participant->name = $$name;
                $participant->birth_date = $$birth_date;
                $participant->email = $$email;
                $participant->identity_card = $$identity_card;
                $participant->status = $$status;
                $participant->phone = $$phone;
                $participant->save();
            }

            $user = new User;
            $user->username = $username;
            $user->team_id = $username;
            $user->password = $password;
            $user->name = $name_team;
            $user->role = "team";
            $user->save();

            return redirect('/login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
