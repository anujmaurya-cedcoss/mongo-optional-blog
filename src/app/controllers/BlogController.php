<?php

use Phalcon\Mvc\Controller;

session_start();
class BlogController extends Controller
{
    // show all the blogs in view
    public function indexAction()
    {
        $output = $this->mongo->blogs->find();
        $data = [];
        foreach ($output as $value) {
            $data[] = $value;
        }
        $this->view->data = json_encode($data);
    }
    public function addAction()
    {
        // redirected to view
    }

    public function getData($id)
    {
        return  $this->mongo->blogs->findOne(['id' => (string)$id]);
    }
    public function displaySingleAction()
    {
        $id = $_GET['id'];
        $output = $this->getData($id);
        $this->view->data = json_encode($output);
    }

    public function editAction()
    {
        $id = $_GET['id'];
        $output = $this->mongo->getData($id);
        $this->view->data = json_encode($output);
    }

    public function updateAction()
    {
        $id = $_GET['id'];
        $output = $this->mongo->blogs->updateOne(
            ['id' => $id],
            ['$set' => $_POST]
        );
        $success = $output->getModifiedCount();
        if ($success > 0) {
            $this->response->redirect('/blog/');
        } else {
            echo "<h3>There was some error</h3>";
            die;
        }
    }
    public function addBlogAction()
    {
        if ($_POST['title'] != '' && $_POST['content'] != '') {
            $_POST['id'] = uniqid();
            $_POST['author']['id'] = $_SESSION['uid'];
            $_POST['author']['name'] = $_SESSION['name'];
            $_POST['date'] = date('Y-m-d');
            $output = $this->mongo->blogs->insertOne($_POST);
            $success = $output->getInsertedCount();
            if ($success > 0) {
                $this->response->redirect('/blog/');
            } else {
                echo "<h3>There was some error</h3>";
                die;
            }
        } else {
            echo "<h3>Values can't be empty</h3>";
            die;
        }
    }
}
