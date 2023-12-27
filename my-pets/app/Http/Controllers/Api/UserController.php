<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\PetList; 
use App\Models\category;
use Illuminate\Support\Facades\Auth; 
use Validator;
 
class UserController extends Controller
{
    public $successStatus = 200;
 /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('Mypets')-> accessToken; 
            $success['userId'] = $user->id;
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
 
 /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role'=>'required',
        ]);

        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }      
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('Mypets')-> accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }
 
 /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function userDetails() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }

    public function addPets(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'pet_name' => 'required',
            'message' => 'required',
            'image' => 'required|image',
            'type'=> 'required',
        ]);

        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }      
        $input = $request->all();
        $user = Auth::user(); 

        if($request->image){
            $imageName = time().'image.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } 
        $input['user_id'] = $user->id;
        $input['image'] = $imageName; 
        $user = PetList::create($input); 
        $success['token'] =  $user->createToken('Mypets')-> accessToken; 
        $success['name'] =  $user->pet_name;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function petDetails(Request $request) 
    { 

        $petDetails = PetList::select('pet_lists.*','users.name')
          ->leftJoin('users', 'pet_lists.user_id', '=', 'users.id')
          ->where('pet_lists.id',$request->pet_id)->first();

          $result=array(
            'image'=>asset('images/'.$petDetails->image),
            'user_name'=>$petDetails->name,
            'message'=>$petDetails->message,
            'type'=>$petDetails->type,
            'pet_name'=>$petDetails->pet_name,
            'pet_id'=>$petDetails->id,
          );

        return response()->json(['success' => $result], $this-> successStatus); 
    }
    public function petList(Request $request) 
    { 

        $petList = PetList::select('pet_lists.*','users.name')
          ->leftJoin('users', 'pet_lists.user_id', '=', 'users.id')
          ->get();

        foreach($petList as $val){
          $result[]=[
            'image'=>asset('images/'.$val->image),
            'user_name'=>$val->name,
            'message'=>$val->message,
            'type'=>$val->type,
            'pet_name'=>$val->pet_name,
            'pet_id'=>$val->id,
          ];
        }

        return response()->json(['success' => $result], $this-> successStatus); 
    }
    public function latestpetList(Request $request) 
    { 

        $petList = PetList::select('pet_lists.*','users.name')
          ->leftJoin('users', 'pet_lists.user_id', '=', 'users.id')
          ->orderBy('id', 'DESC')
          ->limit(6)
          ->get();

        foreach($petList as $val){
          $result[]=[
            'image'=>asset('images/'.$val->image),
            'user_name'=>$val->name,
            'message'=>$val->message,
            'type'=>$val->type,
            'pet_name'=>$val->pet_name,
            'pet_id'=>$val->id,
          ];
        }

        return response()->json(['success' => $result], $this-> successStatus); 
    }
    public function category(Request $request) 
    { 

        $category = category::get();
        $result=[];
        foreach($category as $val){
          $result[]=[
            'pet_type'=>$val->pet_type,
          ];
        }

        return response()->json(['success' => $result], $this-> successStatus); 
    }

    public function addCategory(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'pet_type' => 'required',
        ]);

        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }      
        $input = $request->all();

        $user = category::create($input); 
        $success['token'] =  $user->createToken('Mypets')-> accessToken;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }








}