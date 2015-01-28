<script type="text/javascript" src="<?php echo base_url(); ?>js/directoryIndex.js"></script>

<?php if (isset($exception)): ?>
    <div class="row">
        <div class="col-md-12">
            <div id="exception" class="alert alert-danger">
                <p><?php echo $exception; ?></p>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
                <?php foreach ($subdirectories as $subdirectory) : ?>
                    <li class="list-group-item">
                        <?php echo '<a href="'.base_url().'directories/index/'.$subdirectory->GetId().'"><span class="glyphicon glyphicon-folder-close directory-symbol"></span>'.$subdirectory->GetName().'</a>'; ?>
                        <div class="pull-right">
                            <a href="<?php echo base_url().'directories/edit/'.$subdirectory->GetId(); ?>">
                               <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a class="delete" href="<?php echo base_url().'directories/delete/'.$subdirectory->GetId(); ?>">
                               <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="bookmarks">
                <?php foreach ($bookmarks as $bookmark) : ?>
                    <tr class="bookmark">
                        <td class="bookmark-thumbnail">
                            <a href="<?php echo $bookmark->GetUrl(); ?>" target="_blank">
                                <img class="thumbnail" alt="chargement de l'image..." src="http://www.robothumb.com/src/?url=<?php echo $bookmark->GetUrl(); ?>" />
                            </a>
                        </td>
                        <td class="bookmark-link">
                            <?php echo '<a href="'.$bookmark->GetUrl().'" target="_blank">'.$bookmark->GetTitle().'</a>'; ?>
                            <div class="pull-right">
                                <a href="<?php echo base_url().'bookmarks/edit/'.$bookmark->GetId(); ?>">
                                   <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                                <a class="delete" href="<?php echo base_url().'bookmarks/delete/'.$bookmark->GetId(); ?>">
                                   <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php endif; ?>