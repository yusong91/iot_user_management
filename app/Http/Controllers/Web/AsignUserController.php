<?php
 
namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\AsignUser\AsignUserRepository;

class AsignUserController extends Controller
{ 
    private $asign_user;

    public function __construct(AsignUserRepository $asign_user)
    {  
		$this->asign_user = $asign_user;
	}
 
    public function index()
    {  
        $user_id = auth()->user()->id;
        $projects = getFolders($user_id);
        //return view('asign_user.index');
        dd('ok');
    }

    public function show($id)
    {   
        $user = $this->asign_user->find($id);
        $user_id = $user->id;
        $family_id = $user->role_id == 1 ?  $user->id : $user->family;
        $parent = $this->asign_user->find_parent($user->parent_id);
        $list_asign_to = $this->asign_user->get_list_role($user_id, $user->role_id, $family_id);
        return view('asign_user.asign', compact('id','user', 'list_asign_to', 'parent'));
    }

    public function create()
    {
        // 
    }

    public function store(Request $request)
    {
        
    } 

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $create = $this->asign_user->update($id,$request->all());
        if($create){      
            return redirect(route('asignproject.index'))->withSuccess('Success');
        }
        return redirect(route('asignproject.index'))->withErrors('Fail');
    }

    public function destroy($id)
    {
        $delete = deleteAsign($id); 
        if($delete){      
            return redirect(route('asignproject.index'))->withSuccess('Success');
        }
        return redirect(route('asignproject.index'))->withErrors('Fail');
    }
}
