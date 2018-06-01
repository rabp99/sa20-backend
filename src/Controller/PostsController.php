<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 */
class PostsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $estado_id = $this->request->query('estado_id');
        $text = $this->request->query('text');
        $category_id = $this->request->query('category_id');
        $items_per_page = $this->request->query('items_per_page');
        
        $this->paginate = [
            'limit' => $items_per_page
        ];
        
        $query = $this->Posts->find()
            ->contain(['Categories'])
            ->order(['Posts.id' => 'ASC']);
        
        if ($text) {
            $query->where(['OR' => [
                'Posts.title LIKE' => '%' . $text . '%',
                'Posts.subtitle LIKE' => '%' . $text . '%',
                'Posts.resumen LIKE' => '%' . $text . '%',
                'Posts.body LIKE' => '%' . $text . '%'
            ]]);
        }
        
        if ($category_id) {
            $query->where(['Posts.category_id' => $category_id]);
        }
        
        if ($estado_id) {
            $query->where(['Posts.estado_id' => $estado_id]);
        }
        
        $posts = $this->paginate($query);
        $paginate = $this->request->param('paging')['Posts'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('posts', 'pagination'));
        $this->set('_serialize', ['posts', 'pagination']);
    }
    
    public function previewPortada() {
        if ($this->request->is("post")) {
            $portada = $this->request->data["file"];
            
            $path_dst = WWW_ROOT . "tmp" . DS;
            $ext = pathinfo($portada['name'], PATHINFO_EXTENSION);
            $filename = 'post-' . $this->Random->randomString() . '.' . $ext;
           
            $filename_src = $portada["tmp_name"];
            $file_src = new File($filename_src);

            if ($file_src->copy($path_dst . $filename)) {
                $code = 200;
                $message = 'La portada fue subida correctamente';
            } else {
                $message = "La portada no fue subida con éxito";
            }
            
            $this->set(compact("code", "message", "filename"));
            $this->set("_serialize", ["message", "filename"]);
        }
    }
    
    public function add() {
        if ($this->request->is('post')) {
            $post = $this->Posts->newEntity($this->request->getData());
            
            if ($post->portada) {
                $path_src = WWW_ROOT . "tmp" . DS;
                $file_src = new File($path_src . $post->portada);
            
                $path_dst = WWW_ROOT . 'img' . DS . 'posts' . DS;
                $post->portada = $this->Random->randomFileName($path_dst, 'post-', $file_src->ext());
                
                $file_src->copy($path_dst . $post->portada);
            }
            
            if ($this->Posts->save($post)) {
                $code = 200;
                $message = 'El post fue guardado correctamente';
            } else {
                $errors = $post->errors();
                $code = 500;
                $message = 'El post no fue guardado correctamente';
            }
        }
        
        $this->set(compact('post', 'message', 'code', 'errors'));
        $this->set('_serialize', ['post', 'message', 'code', 'errors']);
    }
}