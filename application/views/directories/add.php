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
            <h3>Nouveau répertoire</h3>
            <br />
            <form class='form-horizontal' role="form" method="post">
                <div class="form-group">
                    <label for="directoryName" class="col-sm-2 control-label">Nom</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="directoryName" name="directoryName" placeholder="Nom du répertoire">
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