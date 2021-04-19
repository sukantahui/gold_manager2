<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this -> load -> model('person');
        $this->load->helper('huiui_helper');
    }
    public function index()
    {
        $this->load->view('public/index_top');
//        $this->load->view('public/index_header');
        $this->load->view('public/index_main');
        $this->load->view('public/index_end');
    }

    public function angular_view_main(){
        $this->load->view('angular_views/main');
    }

    public function validate_credential(){
        $post_data =json_decode(file_get_contents("php://input"), true);
        $result=$this->person->get_employee_by_authentication((object)$post_data);
        $newdata = array(
            'employee_id'  => $result->emp_id,
            'employee_name'     => $result->emp_name,
            'user_type_id'=> $result->user_type_id,
            'is_logged_in'     => $result->is_logged_in,
        );
        $this->session->set_userdata($newdata);
       echo json_encode($newdata,JSON_NUMERIC_CHECK);
    }
    public function show_headers(){
        if($_GET['person_cat_id']==3){
            $this->load->view('menus/index_header_staff');
        }
        if($_GET['person_cat_id']==1){
            $this->load->view('menus/index_header_admin');
        }
    }
    public function angular_view_home(){
        $newdata = array(
            'person_id'  => '',
            'person_name' => '',
            'user_id'=> '',
            'person_cat_id' => 0,
            'is_logged_in-' => 0
        );
        $this->session->set_userdata($newdata);
        ?>
            <style type="text/css">

                @function color($key: "blue") {
                @return map-get($colors, $key);
                }

                @function theme-color($key: "primary") {
                @return map-get($theme-colors, $key);
                }

                @function gray($key: "100") {
                @return map-get($grays, $key);
                }
                .custom-element {
                    color: gray("100");
                    background-color: theme-color("dark");
                }
            </style>

            <div class="row d-flex align-content-center bg-dark">
                <div class="col-10">
                    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                        <form class="form-inline" action="/action_page.php">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search">
                            <button class="btn btn-success" ng-click="pop()" type="button">Search</button>
                            <button ng-click="pop()">Show a Toaster</button>
                        </form>
                    </nav>
                </div>
                <div class="col-2 bg-dark">
                    <a href="#!login" class="text-white">Login <i class="fas fa-sign-in-alt text-white"></i></a>
                </div>
            </div>
           
            <div class="class="row d-flex align-content-center bg-dark"">
                <div class="row bg-dark">
                    <img src="img/kanyashree1.jpg" class="img-fluid m-0"/>
                </div>
            </div>

            <div class="row d-flex bg-dark">
                <div class="p-2 bg-info text-white col-4">
                    What is Lorem Ipsum?
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                </div>
                <div class="p-2 bg-info text-white col-4">

                    Why do we use it?

                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).

                </div>
                <div class="p-2 bg-info text-white col-4">

                    Where does it come from?

                    Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                </div>
            </div>
            <div class="row d-flex bg-dark">
                <div class="p-2 bg-info text-white col-4">
                    What is Lorem Ipsum?
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                </div>
                <div class="p-2 bg-info text-white col-4">

                    Why do we use it?

                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).

                </div>
                <div class="p-2 bg-info text-white col-4">

                    Where does it come from?

                    Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                </div>
            </div>
        <?php
    }
    public function angular_view_login(){
        ?>
        <style type="text/css">
            body,html {
                height: 100%;
                /*background-color: green;*/
                background: linear-gradient(to bottom, #0D3349 0%, #4F5155 100%);
            }
            .card-body{
                background-image: url("img/form-bg.jpg");
                /* Full height */
                height: 100%;

                /* Center and scale the image nicely */
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;

            }
            .card-header{
                background-color: #4e555b;
            }
        </style>
        <div class="row mt-5">
            <div class="col-md-12">
                <h4 class="text-center text-white mb-4"><i class="fa fa-user"></i> User Login</h4>
                <div class="row">
                    <div class="col-md-4 mx-auto">

                        <!-- form card login -->
                        <div class="card rounded-0">
                            <div class="row ml-0 justify-content-end col-12 card-header text-white">
                                <h3><a href="#!" class="text-white"><i class="fas fa-window-close"></i></a></h3>

                            </div>
                            <div class="card-body">
                                <form class="form" role="form" autocomplete="off" id="formLogin" novalidate="" method="POST">
                                    <div class="form-group col-4">
<!--                                        <img src="img/sahaj_path.jpg" class="img-fluid" width="120px" alt="Responsive image">-->
                                        <img src="img/password.png" class="img-fluid" width="140px" alt="Responsive image">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="uname1" class="text-black"><i class="fa fa-user"></i> Username</label>
                                        <input type="text" ng-model="loginData.user_id" class="form-control form-control-lg rounded-0" name="uname1" id="uname1" required="">
                                        <div class="invalid-feedback">Oops, you missed this one.</div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label class="text-black"><i class="fas fa-key"></i> Password</label>
                                        <input type="password" ng-model="loginData.user_password" class="form-control form-control-lg rounded-0" id="pwd1" required="" autocomplete="new-password">
                                        <div class="invalid-feedback">Enter your password too!</div>
                                    </div>
                                    <div class="row form-group col-4 justify-content-end">
                                        <button type="submit" ng-click="login(loginData)" class="btn btn-success btn-lg" id="btnLogin">Login</button>
                                    </div>

                                </form>
                            </div>
                            <!--/card-block-->
                        </div>
                        <!-- /form card login -->
                    </div>
                </div>
                <!--/row-->
            </div>
            <!--/col-->
        </div>
        <!--/row-->




        <?php
    }
}
?>