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
            <h3>Modifier le favori</h3>
            <br />
            <form class='form-horizontal' role="form" method="post">
                <div class="form-group">
                    <label for="url" class="col-sm-2 control-label">Url</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="url" name="url" value="<?php echo $bookmark->GetUrl(); ?>">
                    </div>
                    <label for="title" class="col-sm-2 control-label">Titre</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $bookmark->GetTitle(); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>