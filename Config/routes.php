<?php

//Routes for thumbs
Router::connect('/thumb/*', array('plugin' => 'me_tools', 'admin' => FALSE, 'controller' => 'thumbs', 'action' => 'thumb'));
Router::connect('/square/*', array('plugin' => 'me_tools', 'admin' => FALSE, 'controller' => 'thumbs', 'action' => 'square'));