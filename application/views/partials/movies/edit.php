<h1 class="page-header">Catálogo de películas | editar.</h1>
<div class="row">
   <form action="<?= site_url('movies/update/'); ?>" method="post" id="form-update-movie" enctype="multipart-form-data">
      <!-- field ID MOVIE -->
      <div class="col-md-12">
         <div class="form-group">
            <label>ID:</label>
            <input type="text" class="form-control" value="<?= $id_movie_encryp; ?>" disabled>
            <input type="hidden" id="id_movie_update" name="id_movie_update" class="form-control" value="<?= $id_movie_encryp; ?>">
         </div>
      </div>
      <!-- END field ID MOVIE -->

      <!-- field PRODUCTORS MOVIE -->
      <div class="col-md-4">
         <div class="form-group">
            <?php if ($productors_movie != FALSE) : ?>
               <?php
               $productors_selected = array();
               foreach ($productors_movie as $key => $data_element) {
                  $productors_selected[] = $data_element->id_productor;
               }
               ?>
               <select id="ids_productors_update" name="ids_productors_update[]" multiple class="form-control" required>
                  <?php foreach ($get_all_productors_activated->result() as $key => $productor_select) : ?>
                     <?php if (in_array($productor_select->id_productor, $productors_selected)) : ?>
                        <option value="<?= $productor_select->id_productor; ?>" selected="selected"><?= $productor_select->productor_name; ?></option>
                     <?php else : ?>
                        <option value="<?= $productor_select->id_productor; ?>"><?= $productor_select->productor_name; ?></option>
                     <?php endif ?>
                  <?php endforeach ?>
               </select>
            <?php else : ?>
               <select id="ids_productors_update" name="ids_productors_update[]" multiple class="form-control" required>
                  <?php foreach ($get_all_productors_activated->result() as $key => $productor) : ?>
                     <option value="<?= $productor->id_productor; ?>"><?= $productor->productor_name; ?></option>
                  <?php endforeach ?>
               </select>
            <?php endif ?>
         </div>
      </div>
      <!-- END field PRODUCTORS MOVIE -->

      <!-- field GENDERS MOVIE -->
      <div class="col-md-4">
         <div class="form-group">
            <?php if ($genders_movie != FALSE) : ?>
               <?php
               $genders_selected = array();
               foreach ($genders_movie as $key => $data_element) {
                  $genders_selected[] = $data_element->id_gender;
               }
               ?>
               <select id="ids_genders_update" name="ids_genders_update[]" multiple class="form-control" required>
                  <?php foreach ($get_all_genders_activated->result() as $key => $gender_select) : ?>
                     <?php if (in_array($gender_select->id_gender, $genders_selected)) : ?>
                        <option value="<?= $gender_select->id_gender; ?>" selected="selected"><?= $gender_select->gender_name; ?></option>
                     <?php else : ?>
                        <option value="<?= $gender_select->id_gender; ?>"><?= $gender_select->gender_name; ?></option>
                     <?php endif ?>
                  <?php endforeach ?>
               </select>
            <?php else : ?>
               <select id="ids_genders_update" name="ids_genders_update[]" multiple class="form-control" required>
                  <?php foreach ($get_all_genders_activated->result() as $key => $gender) : ?>
                     <option value="<?= $gender->id_gender; ?>"><?= $gender->gender_name; ?></option>
                  <?php endforeach ?>
               </select>
            <?php endif ?>
         </div>
      </div>
      <!-- END field GENDERS MOVIE -->

      <!-- field CATEGORYS MOVIE -->
      <div class="col-md-4">
         <div class="form-group">
            <?php if ($categories_movie != FALSE) : ?>
               <?php
               $categories_selected = array();
               foreach ($categories_movie as $key => $data_element) {
                  $categories_selected[] = $data_element->id_category;
               }
               ?>
               <select id="ids_categories_update" name="ids_categories_update[]" multiple class="form-control" required>
                  <?php foreach ($get_all_categories_activated->result() as $key => $category_select) : ?>
                     <?php if (in_array($category_select->id_category, $categories_selected)) : ?>
                        <option value="<?= $category_select->id_category; ?>" selected="selected"><?= $category_select->category_name; ?></option>
                     <?php else : ?>
                        <option value="<?= $category_select->id_category; ?>"><?= $category_select->category_name; ?></option>
                     <?php endif ?>
                  <?php endforeach ?>
               </select>
            <?php else : ?>
               <select id="ids_categories_update" name="ids_categories_update[]" multiple class="form-control" required>
                  <?php foreach ($get_all_categories_activated->result() as $key => $category) : ?>
                     <option value="<?= $category->id_category; ?>"><?= $category->category_name; ?></option>
                  <?php endforeach ?>
               </select>
            <?php endif ?>
         </div>
      </div>
      <!-- END field CATEGORYS MOVIE -->

      <!-- field STATUS NAME -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Estatus:</label>
            <select id="movie_status_update" name="movie_status_update" class="form-control" required>
               <option value="<?= $edit_movie->id_status; ?>"><?= $edit_movie->status_name; ?></option>
               <?php foreach ($get_all_status->result() as $key => $value) : ?>
                  <?php if ($value->id_status != $edit_movie->id_status) : ?>
                     <option value="<?= $value->id_status; ?>"><?= $value->status_name; ?></option>
                  <?php endif ?>
               <?php endforeach ?>
            </select>
         </div>
      </div>
      <!-- END field STATUS NAME -->

      <!-- field MOVIE NAME -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Título:</label>
            <input type="text" id="movie_name_update" name="movie_name_update" class="form-control" value="<?= $edit_movie->movie_name; ?>" required minlength="3" maxlength="50" autocomplete="off">
         </div>
      </div>
      <!-- END field MOVIE NAME -->

      <!-- field MOVIE SLUG -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Alias:</label>
            <input type="text" id="movie_slug_update" name="movie_slug_update" class="form-control" value="<?= $edit_movie->movie_slug; ?>" required minlength="3" maxlength="50" readonly>
         </div>
      </div>
      <!-- END field MOVIE SLUG -->

      <!-- field QUALITY NAME -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Calidad:</label>
            <select id="movie_quality_update" name="movie_quality_update" class="form-control" required>
               <option value="<?= $edit_movie->id_quality; ?>"><?= $edit_movie->quality_name; ?></option>
               <?php foreach ($get_all_qualities_activated->result() as $key => $value) : ?>
                  <?php if ($value->id_quality != $edit_movie->id_quality) : ?>
                     <option value="<?= $value->id_quality; ?>"><?= $value->quality_name; ?></option>
                  <?php endif ?>
               <?php endforeach ?>
            </select>
         </div>
      </div>
      <!-- END field QUALITY NAME -->

      <!-- field MOVIE RELEASE DATE -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Fecha de lanzamiento:</label>
            <div class="input-group">
               <input type="text" id="movie_release_date_update" name="movie_release_date_update" class="form-control" value="<?= $edit_movie->movie_release_date; ?>" required autocomplete="off">
               <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
               </span>
            </div>
         </div>
      </div>
      <!-- END field MOVIE RELEASE DATE -->

      <!-- field MOVIE DURATION -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Duración:</label>
            <input type="text" id="movie_duration_update" name="movie_duration_update" class="form-control" value="<?= $edit_movie->movie_duration; ?>" required>
         </div>
      </div>
      <!-- END field MOVIE DURATION -->

      <!-- field MOVIE COUNTRY ORIGIN -->
      <div class="col-md-8">
         <div class="form-group">
            <label>País de origen:</label>
            <select id="movie_country_origin_update" name="movie_country_origin_update" class="form-control" required>
               <?php foreach (get_all_countries() as $key => $value) : ?>
                  <?php if (strcmp($edit_movie->movie_country_origin, $value) == 0) : ?>
                     <option value="<?= $value; ?>" selected><?= $value; ?></option>
                  <?php else : ?>
                     <option value="<?= $value; ?>"><?= $value; ?></option>
                  <?php endif ?>
               <?php endforeach ?>
            </select>
         </div>
      </div>
      <!-- END field MOVIE COUNTRY ORIGIN -->

      <!-- field MOVIE COVER -->
      <div class="col-md-4">
         <div class="form-group">
            <label>Portada:</label>
            <input type="file" id="movie_cover_update" name="movie_cover_update" class="form-control">
            <input type="hidden" id="image_cover_update_route" name="image_cover_update_route" class="form-control" value="<?= $edit_movie->movie_cover; ?>" readonly>
         </div>
      </div>

      <div class="modal fade" id="modal-movie-cover">
         <div class="modal-dialog modal-sm">
            <div class="modal-content">
               <div class="modal-header bg-black">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title text-white text-center">Ver portada</h4>
               </div>
               <div class="modal-body">
                  <img id="preview-img-cover" class="img-responsive img-rounded" style="width: 100%; height: 315px;">
               </div>
               <div class="modal-footer bg-black">
                  <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-usage-image-cover"><span class="glyphicon glyphicon-picture"></span> Usar</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle"></span> Cerrar</button>
               </div>
            </div>
         </div>
      </div>
      <!-- END field MOVIE COVER -->

      <!-- field MOVIE PLAY -->
      <div class="col-md-12">
         <div class="form-group">
            <label>Enlace:</label>
            <input type="text" id="movie_play_update" name="movie_play_update" class="form-control" required minlength="30" value="<?= $edit_movie->movie_play; ?>">
         </div>
      </div>
      <!-- END field MOVIE PLAY -->

      <!-- field MOVIE DESCRIPTION -->
      <div class="col-md-12">
         <div class="form-group">
            <label>Descripción:</label>
            <textarea type="text" id="movie_description_update" name="movie_description_update" class="form-control "><?= $edit_movie->movie_description; ?></textarea>
         </div>
      </div>
      <!-- END field MOVIE DESCRIPTION -->

      <!-- field DATE MODIFIED MOVIE -->
      <div class="col-md-6">
         <div class="form-group">
            <label>Fecha de modificación:</label>
            <input type="text" class="form-control" value="<?= get_date_current(); ?>" disabled>
         </div>
      </div>
      <!-- END field DATE MODIFIED MOVIE -->

      <!-- field IP MODIFIED MOVIE -->
      <div class="col-md-6">
         <div class="form-group">
            <label>IP de modificación:</label>
            <input type="text" class="form-control" value="<?= get_ip_current(); ?>" disabled>
         </div>
      </div>
      <!-- END field IP MODIFIED MOVIE -->

      <!-- field CLIENT MODIFIED MOVIE -->
      <div class="col-md-12">
         <div class="form-group">
            <label>Dispositivo de modificación:</label>
            <textarea type="text" class="form-control " disabled><?= get_agent_current(); ?></textarea>
         </div>
      </div>
      <!-- END field CLIENT MODIFIED MOVIE -->

      <!-- buttons ACTIONS -->
      <div class="col-md-4">
         <button type="submit" class="btn btn-info" id="btn-update-movie"><span class="glyphicon glyphicon-refresh"></span> Actualizar</button>
         <a href="<?= site_url('movies/'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Cancelar</a>
      </div>
      <!-- END buttons ACTIONS -->
   </form>
</div>