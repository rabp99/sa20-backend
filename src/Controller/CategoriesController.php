<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Category[] paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $estado_id = $this->request->query('estado_id');
        $text = $this->request->query('text');
        $items_per_page = $this->request->query('items_per_page');
        
        $this->paginate = [
            'limit' => $items_per_page
        ];
        
        $query = $this->Categories->find()
            ->order(['Categories.id' => 'ASC']);
        
        if ($text) {
            $query->where(['Categories.descripcion LIKE' => '%' . $text . '%']);
        }
        
        if ($estado_id) {
            $query->where(['Categories.estado_id' => $estado_id]);
        }
        
        $categories = $this->paginate($query);
        $paginate = $this->request->param('paging')['Categories'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('categories', 'pagination'));
        $this->set('_serialize', ['categories', 'pagination']);
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Estados']
        ]);

        $this->set('category', $category);
        $this->set('_serialize', ['category']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $category = $this->Categories->newEntity();
        
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            
            if ($category->portada) {
                $pathSrc = WWW_ROOT . "tmp" . DS;
                $fileSrc = new File($pathSrc . $category->portada);
             
                $pathDst = WWW_ROOT . 'img' . DS . 'categories' . DS;
                $category->portada= $this->Random->randomFileName($pathDst, 'category-', $fileSrc->ext());
                
                $fileSrc->copy($pathDst . $category->portada);
            }
            
            if ($this->Categories->save($category)) {
                $code = 200;
                $message = 'La categoría fue guardado correctamente';
            } else {
                $message = 'La categoría no fue guardado correctamente';
            }
        }
        
        $this->set(compact('category', 'message', 'code'));
        $this->set('_serialize', ['category', 'message', 'code']);
    }

    /**
     * Preview Portada method
     *
     * @return \Cake\Http\Response|null
     */
    public function previewPortada() {
        if ($this->request->is("post")) {
            $portada = $this->request->data["file"];
            
            $pathDst = WWW_ROOT . "tmp" . DS;
            $ext = pathinfo($portada['name'], PATHINFO_EXTENSION);
            $filename = 'category-' . $this->Random->randomString() . '.' . $ext;
            
            $filenameSrc = $portada["tmp_name"];
            $fileSrc = new File($filenameSrc);
            if ($fileSrc->copy($pathDst . $filename)) {
                $code = 200;
                $message = 'La portada fue subida correctamente';
            } else {
                $message = "La portada no fue subida con éxito";
            }
            
            $this->set(compact("code", "message", "filename"));
            $this->set("_serialize", ["message", "filename"]);
        }
    }
}
