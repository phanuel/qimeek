<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Qimeek</title>
        <link href="<?php echo base_url(); ?>lib/bootstrap-3.1.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" >
        <script src="<?php echo base_url(); ?>lib/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>lib/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/stats.js"></script>
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php if ($this->session->userdata('validated')): ?>
            <div class="container">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                      <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                          <span class="sr-only">Toggle navigation</span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src='<?php echo base_url(); ?>images/logo.png' class='navbar-logo' /> Qimeek</a>
                      </div>
                      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                          <li <?php echo ($this->uri->segment(1) == "directories" || $this->uri->segment(1) == "bookmarks") ? "class='active'" : ""; ?>><a href="<?php echo base_url(); ?>directories"><span class="glyphicon glyphicon-star"></span> Favoris</a></li>
                          <?php if (isset($directory)): ?>
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus"></span> Ajouter <b class="caret"></b></a>
                              <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url(); ?>directories/add/<?php echo $directory->GetId(); ?>"><span class="glyphicon glyphicon-folder-close"></span> Répertoire...</a></li>
                                <li><a href="<?php echo base_url(); ?>bookmarks/add/<?php echo $directory->GetId(); ?>"><span class="glyphicon glyphicon-star"></span> Favori...</a></li>
                              </ul>
                            </li>
                          <?php endif; ?>
                        </ul>
                        <form class="navbar-form navbar-left" role="search" action="<?php echo base_url(); ?>bookmarks/search" method="post">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-search"></span></span>
                                <input type="text" name="input" class="form-control" placeholder="Rechercher..." <?php echo (isset($input)) ? "value='".$input."'": ""; ?>>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">ok</button>
                                </span>
                            </div>
                        </form>
                        <ul class="nav navbar-nav navbar-right">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $this->session->userdata('login'); ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                              <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> Mon compte</a></li>
                              <li class="divider"></li>
                              <li><a href="<?php echo base_url(); ?>login/do_logout"><span class="glyphicon glyphicon-off"></span> Déconnexion</a></li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                </nav>
                <?php if (isset($directory)): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <ol class="breadcrumb">
                                <span class="glyphicon glyphicon-folder-open current-directory-symbol"></span>
                                <?php $parentDirectories = $directory->GetParents(); ?>
                                <?php foreach ($parentDirectories as $parentDirectory): ?>
                                    <li>
                                        <a href="<?php echo base_url().'directories/index/'.$parentDirectory->GetId(); ?>"><?php echo $parentDirectory->GetName(); ?></a>
                                    </li>
                                <?php endforeach; ?>
                                <li>
                                    <?php echo $directory->GetName(); ?>
                                </li>
                                <?php if ($directory->GetParent() != null): ?>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url().'directories/edit/'.$directory->GetId(); ?>">
                                           <span class="glyphicon glyphicon-pencil"></span>
                                        </a>
                                        <a class="delete" href="<?php echo base_url().'directories/delete/'.$directory->GetId(); ?>">
                                           <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                <?php elseif (isset($input)): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <ol class="breadcrumb">
                                <span class="glyphicon glyphicon-search current-directory-symbol"></span>
                                <li>
                                    Recherche: <b><?php echo $input; ?></b>
                                </li>
                            </ol>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div id="header" class="jumbotron">
                <div class="container">
                    <h1><img src='<?php echo base_url(); ?>images/logo.png' class="main-logo" /> Qimeek</h1>
                </div>
            </div>
        <?php endif; ?>
        <div class="container">
            <?php echo isset($content) ? $content : ""; ?>
        </div>
        <div id="footer">
            <div class="container">
                <hr />
                <p>Qimeek v3.0.0 | © Copyright <?php echo date("Y"); ?> Qimeek | Screenshots by <a href="http://www.robothumb.com" onclick="this.target='_blank'">Robothumb</a></p>
            </div>
        </div>
    </body>
</html>
