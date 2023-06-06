<?php

use Phalcon\Mvc\Controller;

session_start();
class IndexController extends Controller
{
    public function indexAction()
    {
        // redirected to view
    }

    public function signupAction()
    {
        if ($_POST['name'] != '' && $_POST['email'] != '' && $_POST['password'] != '') {
            $_POST['uid'] = uniqid();
            $output = $this->mongo->users->insertOne($_POST);
            $success = $output->getInsertedCount();
            if ($success > 0) {
                $this->response->redirect('/index/login');
            } else {
                echo "There was some error";
                die;
            }
        } else {
            echo "<h3>Please fill all the details !</h3>";
            die;
        }
    }

    public function loginAction()
    {
        // redirected to view
    }

    public function doLoginAction()
    {
        $output = $this->mongo->users->findOne(
            ['email' => $_POST['email'], 'password' => $_POST['password']]
        );
        if ($output['name'] != '') {
            $_SESSION['uid'] = $output['uid'];
            $_SESSION['role'] = $output['role'];
            $_SESSION['name'] = $output['name'];
            $this->response->redirect('/blog/');
        } else {
            echo "<h3>Invalid Credentials</h3>";
            die;
        }
    }
}
