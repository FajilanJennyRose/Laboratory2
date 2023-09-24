<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MusicModel;
use App\Models\PlaylistModel;
 
class MusicController extends BaseController
{
    public function index()
    {
        $main = new MusicModel();
        $data['music'] = $main->findAll();
        $data['mus']= [];
        return view('music',$data);
    }
    public function searchsong()
    {
        $searchQuery = $this->request->getVar('search');

        if ($searchQuery) {
            $main = new MusicModel();
            $data['mus'] = $main->like('musicname', $searchQuery)->findAll();
        }
        return view('music', $data);
    }
    public function addsong()
    {
        if($this->request->getMethod() == 'post'){
            $rules = [
                    'myfile' => [
                        'rules' => 'uploaded[myfile]',
                        'label' => 'My File'
                    ]
                ];
            if($this->validate($rules)){
                $file = $this->request->getFile('myfile');
                $filename = pathinfo($file->getName(), PATHINFO_FILENAME);
                $main = new MusicModel();
                $data['music'] = $main->findAll();
                $data['mus'] = [];
                $datatoadd = [
                    'musicname' => $filename,
                    'onplaylist' => "0",
                ];
                $main->save($datatoadd);
                if($file->isValid() && !$file->hasMoved()){
                    $file->move('./music');
                }
                return redirect()->to('/main');
                exit();
            }
        }
    }
    public function createplaylist()
    {
        if($this->request->getMethod() == 'post'){
            $play = new PlaylistModel();
            $data = [
                'playlistname' => $this->request->getVar('playlistName'),
                'onplaylist' => "0"
            ];
            $play->save($data);
            return redirect()->to('/main');
        }
    }
}
