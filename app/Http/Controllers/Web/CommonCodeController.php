<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Model\CommonCode;
use Vanguard\Http\Requests\CommonCode\CreateRequest;
use Vanguard\Http\Requests\CommonCode\MenuRequest;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\CommonCode\UpdateRequest;
use Vanguard\Repositories\CommonCode\CommonCodeRepository;
use Illuminate\Support\Facades\Storage;
use Validator;

class CommonCodeController extends Controller
{

    private $breadcrumbs;

    public function __construct()
    {
        $this->middleware('auth');

        $this->breadcrumbs = [
            [ 'label' => __('លេខកូដប្រព័ន្ធ'), 'link' => route('common-codes.index'), 'isActive' => true ],
        ];
    }

    public function index(){

        return view('common-code.index', [
            'parentCommonCode' => null,
            'commonCodes' => $this->getCommonCodeCollection(),
            'breadcrumbs' => $this->getBreadcrumbs()
        ]);
    }

    public function show($id)
    {
        $commonCode = CommonCode::find($id);
        return view('common-code.index', [
            'parentCommonCode' => $commonCode,
            'commonCodes' => $this->getCommonCodeCollection($commonCode->id),
            'breadcrumbs' => $this->getBreadcrumbs($commonCode)
        ]);
    }

    public function create(Request $request){
        $parentCommonCode = CommonCode::find($request->parent_id);

        $this->breadcrumbs[0]['isActive'] = false;
        return view('common-code.add-edit',[
            'parentCommonCode' => $parentCommonCode,
            'breadcrumbs' => $this->getBreadcrumbs($parentCommonCode, true),
        ]);
    }

    public function store(CreateRequest $request){
        
        $file_name = null;

        if($request->file('image')){
            
            $validator = Validator::make($request->all(), [

                'image'=> 'required|mimes:jpeg,jpg,png'
            ]); 

            if ($validator->passes()) {

                $image = $request->file('image');

                $file_name = $image->getClientOriginalName();

                $save_path = storage_path('app/images');

                $image->move($save_path, $file_name); 
            }
        }

        $commonCode = CommonCode::create(['user_id'=>auth()->user()->id, 'key'=>$request->input('key'), 'value'=>$request->input('value'), 'image'=>$file_name, 'parent_id'=>$request->input('parent_id')]);

        if ($commonCode->parent_id) {
            return redirect()->route('common-codes.show', $commonCode->parent)->withSuccess(__('បង្កើតកូដនិងតម្លៃសម្រាប់ប្រព័ន្ធបានជោគជ័យ'));
        }

        return redirect()->route('common-codes.index')->withSuccess(__('បង្កើតកូដនិងតម្លៃសម្រាប់ប្រព័ន្ធបានជោគជ័យ'));
    }

    public function edit($id)
    {
        $commonCode = CommonCode::find($id);
        $this->breadcrumbs[0]['isActive'] = false;
        return view('common-code.add-edit', [
            'parentCommonCode' => $commonCode,
            'commonCode' => $commonCode,
            'breadcrumbs' => $this->getBreadcrumbs($commonCode->parent, true)
        ]);
    }

    public function update($id, UpdateRequest $request)
    {
        $commonCode = CommonCode::find($id);
        $commonCode->update($request->all());

        if ($commonCode->parent_id) {
            return redirect()->route('common-codes.show',$commonCode->parent)->withSuccess(__('កែប្រែកូដនិងតម្លៃបានជោគជ័យ'));
        }

        return redirect()->route('common-codes.index')->withSuccess(__('កែប្រែកូដនិងតម្លៃបានជោគជ័យ'));
    }

    public function destroy($id)
    {
        $commonCode = CommonCode::find($id);
        $commonCode->delete();

        return redirect()->back()->withSuccess(__('លុបកូដនិងតម្លៃបានជោគជ័យ'));
    }

    private function getCommonCodeCollection($parentId = 0) {
        return CommonCode::withCount('children')
            ->whereParentId($parentId)
            ->orderBy('ordering', 'asc')
            ->paginate(20);
    }

    private function getBreadcrumbs(CommonCode $commonCode = null, $addEditPage = false) {
        if ($commonCode) {
            if ($commonCode->parent) {
                $this->getBreadcrumbs($commonCode->parent, $addEditPage);
            }

            $this->breadcrumbs[] = ['label' => $commonCode->value, 'link' => route('common-codes.show', $commonCode->id), 'isActive' => true];
            $this->breadcrumbs[count($this->breadcrumbs) - ($addEditPage ? 1 : 2)]['isActive'] = false;
        }

        return $this->breadcrumbs;
    }

    public function updateOrder(Request $request) {

        DB::beginTransaction();
        try {
            CommonCode::setNewOrder($request->get('item'));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'ផ្លាស់ប្ដូរលំដាប់លេខកូដប្រព័ន្ធបានជោគជ័យ'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
