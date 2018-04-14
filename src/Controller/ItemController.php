<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 */

namespace Controller;

use Model\Item;
use Model\ItemManager;

/**
 * Class ItemController
 * @package Controller
 */
class ItemController extends AbstractController
{
    protected $files = [];

    public function index()
    {
        return $this->twig->render('Item/index.html.twig', ['errors' => $this->errors]);
    }

    public function add()
    {

        $files=$_FILES['files'];
        if($files['error'][0] === 4){
            $this->errors['fichierVide'] = "Vous n'avez pas entré de fichiers !";
        } else {
            for($i=0; $i<count($files['name']); $i++) {
                $error = false;
                if ($files['size'][$i] > 1000000) {
                    $this->errors['fichierTaille'] = "L'un des fichiers est trop grand !";
                    $error = true;
                }
                if (!in_array($files['type'][$i],['image/gif', 'image/jpeg', 'image/jpg', 'image/png'] )) {
                    $this->errors['fichierType'] = "L'un des fichiers n'est pas au bon format !";
                    $error = true;
                }
                if(!$error) {
                    move_uploaded_file($files['tmp_name'][$i], "assets/images/image".uniqid());
                    header('Location: /show');
                }
            }
        }
        return $this->twig->render('Item/index.html.twig', ['errors' => $this->errors]);
    }

    public function show()
    {
        $scanUploads = scandir("assets/images/");
        unset($scanUploads[array_search('.', $scanUploads)]);
        unset($scanUploads[array_search('..', $scanUploads)]);
        return $this->twig->render('Item/show.html.twig', ['images' => $scanUploads]);
    }

    public function delete()
    {
        if(isset($_POST['delete'])) {
            $fichierDel = "assets/images/" . $_POST['delete'];
            if (file_exists($fichierDel)) {
                unlink($fichierDel);
            }
        }

        header('Location: /show');
    }

    public function edit(int $id)
    {
        // TODO : edit item with id $id
        return $this->twig->render('Item/edit.html.twig', ['item', $id]);
    }
}
