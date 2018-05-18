<?php
namespace App\Controller;

use App\Controller\AppController;

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
}