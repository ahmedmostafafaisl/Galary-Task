<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Upload_Files;
use App\Models\Album;
use App\Models\AlbumImage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AlbumImageController extends Controller
{
    use Upload_Files;

    public function index(Request $request, $id)
    {

        $album = Album::findOrFail($id);
        if ($request->ajax()) {
            $rows = AlbumImage::query()->where('album_id', $id)->latest();
            return Datatables::of($rows)
                ->addColumn('action', function ($row) {


                    return '
                            <button   class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="las la-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button   class="btn rounded-pill btn-danger waves-effect waves-light delete"
                                    data-id="' . $row->id . '">
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="las la-trash-alt"></i>
                                </span>
                            </span>
                            </button>
                       ';
                })
                ->addColumn('image', function ($admin) {
                    return ' <img height="60px" src="' . get_file($admin->image) . '" class=" w-60 rounded"
                             onclick="window.open(this.src)">';
                })
                ->editColumn('created_at', function ($row) {
                    return date('Y/m/d', strtotime($row->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.albums.images.index', compact('album'));
    }


    public function create(Request $request)
    {
        return view('Admin.CRUDS.albums.images.parts.create', ['id' => $request->id]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|min:3|max:30',
                'image' => 'required|mimes:jpeg,jpg,png,gif',
                'album_id' => 'required|exists:albums,id',
            ],
            [
                'title.required' => 'Album Name Required',
                'title.unique' => 'Album Name Exists',
                'title.min' => 'min 3 char',
                'title.max' => 'max 30 char',
                'image.required' => 'Image Required',
                'image.mimes' => 'image Ext jpeg,jpg,png,gif',
                'album_id.required' => 'Album Name Required',
                'album_id.exists' => 'Album Name Not Exists',


            ]
        );

        $data["image"] = $this->uploadFiles('albums', $request->file('image'), null);

        AlbumImage::create($data);


        return response()->json(
            [
                'code' => 200,
                'message' => 'done'
            ]
        );
    }


    public function edit($id)
    {
        $image = AlbumImage::findOrFail($id);
        return view('Admin.CRUDS.albums.images.parts.edit', compact('image'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate(
            [
                'title' => 'required|min:3|max:30',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif',
            ],
            [
                'title.required' => 'Album Name Required',
                'title.unique' => 'Album Name Exists',
                'title.min' => 'min 3 char',
                'title.max' => 'max 30 char',
                'image.mimes' => 'image Ext jpeg,jpg,png,gif',
            ]

        );

        $row = AlbumImage::findOrFail($id);
        if ($request->has('image')) {
            $data["image"] = $this->uploadFiles('albums', $request->file('image'), null);
        }

        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'done',
            ]
        );
    }


    public function destroy($id)
    {

        $row = AlbumImage::findOrFail($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'done'
            ]
        );
    } //end fun


}