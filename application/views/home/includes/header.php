<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Question Page</title>
        <?php $this->load->view('home/includes/styles'); ?>
    </head>
    <body>
        <div class="mobile-menu-overlay"></div>
        <div class="header-section">
            <div class="container">
                <header class="header-wrapper">
                    <div class="logo-main">
                        <a href="https://www.gobrainwiz.in/" class="header-logo">
                            <img src="<?php echo base_url('assets/home/images/main-logo.svg'); ?>" class="logo" alt="Logo" />
                        </a>
                    </div>
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="header-right-group">
                        <nav class="main-nav">
                            <ul class="nav-menu">
                                <li><a href="https://www.gobrainwiz.in/" class="nav-link">Home</a></li>
                                <li><a href="https://www.gobrainwiz.in/Aboutus.html" class="nav-link">About us</a></li>
                                <li><a href="https://gobrainwiz.in/question_of_the_day/" class="nav-link">Question of the day</a></li>
                                <li class="nav-dropdown">
                                    <a href="#" class="nav-link dropdown-toggle">
                                        Courses
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="https://www.gobrainwiz.in/pages/crt" class="dropdown-item">CRT</a></li>
                                        <li><a href="https://brainwiz.classx.co.in/new-courses/2-campus-recruitment-training" class="dropdown-item">Online CRT Course</a></li>
                                        <li><a href="https://www.gobrainwiz.in/pages/amcat" class="dropdown-item">AMCAT</a></li>
                                        <li><a href="https://www.gobrainwiz.in/pages/elitmus" class="dropdown-item">E-Litmus</a></li>
                                        <li><a href="https://www.gobrainwiz.in/elitmus/companypapers" class="dropdown-item">E-Litmus questions</a></li>
                                        <li><a href="https://www.gobrainwiz.in/pages/cocubes" class="dropdown-item">CoCubes</a></li>
                                        <li><a href="https://www.gobrainwiz.in/pages/classroomtraining" class="dropdown-item">Classroom Training</a></li>
                                        <li><a href="https://www.gobrainwiz.in/pages/onlinetraining" class="dropdown-item">Online Training</a></li>
                                        <li><a href="javascript:;" class="dropdown-item">Campus Connect</a></li>
                                    </ul>
                                </li>
                                <li><a href="https://brainwiz.classx.co.in/new-courses" class="nav-link">Learn Now</a></li>
                            </ul>
                        </nav>
                        <div class="header-actions">
                            <a href="https://college.gobrainwiz.in" class="login-btn">
                                <i class="fa-solid fa-arrow-left"></i>
                                LOGIN
                            </a>
                        </div>
                    </div>
                </header>
            </div>
        </div>