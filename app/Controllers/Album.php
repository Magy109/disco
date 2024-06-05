<?php

namespace App\Controllers;

use App\Models\AlbumModel;
use App\Models\GenreModel;

class Album extends BaseController
{
    public function index()
    {  
        $albumModel = new AlbumModel();
        $albums = $albumModel->findAll();
        
        $genreModel = new GenreModel(); 
        foreach ($albums as &$album) {
            $genre = $genreModel->find($album->genre_id);
            $album->genre_name = $genre->name;
        }
        
        $data['albums'] = $albums; 
        $data['genres'] = $genreModel->findAll();
    
        return view('album', $data);
    }
    

    public function new()
    {
        return view('albums/new');
    }

    public function save()
    {
        $name = $this->request->getPost('name');
        $author = $this->request->getPost('author');
        $genre_id = $this->request->getPost('genre_id');
        
        $albumModel = new AlbumModel();
        $album = new \App\Entities\Album();
        $album->name = $name;
        $album->author = $author;
        $album->genre_id = $genre_id;
        
        $albumModel->save($album);
        
        return redirect()->to('albums');
    }

    public function delete($id)
    {
        $albumModel = new AlbumModel();        
        if ($albumModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete album'], 500);
        }
    }

    public function get($id)
    {
        $albumModel = new AlbumModel();
        $genreModel = new GenreModel();
        
        $album = $albumModel->find($id);
        $genres = $genreModel->findAll();

        if ($album) {
            return $this->response->setJSON([
                'status' => 'success',
                'album' => $album,
                'genres' => $genres
            ]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Album not found'], 404);
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $author = $this->request->getPost('author');
        $genre_id = $this->request->getPost('genre_id');

        if (!empty($id) && !empty($name)) {
            $albumModel = new AlbumModel();
            $album = $albumModel->find($id);
            $album->name = $name;
            $album->author = $author;
            $album->genre_id = $genre_id;
            $albumModel->save($album);
        }

        return redirect()->to('albums');
    }
}




