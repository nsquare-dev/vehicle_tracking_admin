<?php

    if(isset($header) && $header)
      $this->load->view('header');
		
    if(isset($sidebar) && $sidebar)
    $this->load->view('sidebar');

    if(isset($_view))
        $this->load->view($_view);

    if(isset($footer) && $footer)
        $this->load->view('footer');