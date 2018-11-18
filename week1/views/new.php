<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT18/week1/css/main.css">

        <title><?= $page_title ?></title>
    </head>
    <body>
        <!-- Menu -->
        <?= $navigation ?>

        <!-- Content -->
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="pd-15">&nbsp</div>
            <?= $breadcrumbs ?>

            <div class="row">
                <!-- Left column -->
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>

                    <h1><?= $page_title ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                    <form action=<?php echo $form_action ?> method="POST">

                        <?php if (isset($serie_id)){
                            echo "<input type='hidden' class= 'form-control' name='inputId'  value=".$serie_id." required>";
                        } ?>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-formlabel">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputName"  value="<?php if (isset($series_info)){echo $series_info['name'];} ?>"  required>
                        </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputCreator" class="col-sm-2 col-formlabel">Creator</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputCreator" value="<?php if (isset($series_info)){echo $series_info['creator'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSeasons" class="col-sm-2 col-formlabel">Seasons</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="inputSeasons" value="<?php if (isset($series_info)){echo $series_info['seasons'];} ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputAbstract" class="col-sm-2 col-formlabel">Abstract</label>
                            <div class="col-sm-10">
                                 <textarea class="form-control" name="inputAbstract" required><?php if (isset($series_info)){echo $series_info['abstract'];} ?> </textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="submit" class="btn btn-primary"><?= $submit_btn ?></button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right column -->
                <div class="col-md-4">

                    <?php include $right_column ?>

                </div>

            </div>
        </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
