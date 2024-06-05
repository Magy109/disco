<?php

namespace App\Controllers;
use App\Models\GenreModel;

class Genre extends BaseController
{
    public function index()
    {
        $genreModel = model(GenreModel::class);        
        $genres = $genreModel->findAll();        
        
        $data['genres'] = $genres;               
        return view('genre/index', $data);        
    }

    public function new(){
        return view('genre/new');
    }

    public function save(){        
        $name = $this->request->getPost('name');
        $genreModel = model(GenreModel::class);
        $genre = new \App\Entities\Genre();
        $genre->name = $name;        
        $genreModel->save($genre);        
    }

    public function delete($id){
        $genreModel = model(GenreModel::class);        
        $genre = $genreModel->find($id);        
        $genreModel->delete($id);        
        return redirect()->to('/Genre');
    }

    public function edit($id){
        $genreModel = model(GenreModel::class);        
        $data['genre'] = $genreModel->find($id);        
        return view('genre/edit', $data);
    }

    public function update(){
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');

        // Validar que se recibe el ID y el nombre
        if (!empty($id) && !empty($name)) {
            $genreModel = model(GenreModel::class);        
            $genreU = $genreModel->find($id);
            $genreU->name = $name;
            $genreModel->save($genreU); 
        }

        return redirect()->to('/Genre');
    }
}
?>
