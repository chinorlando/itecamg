<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Seleccione la carrera, materia y paralelo que tiene asignado.</h5>
    </div>
    <div class="panel-body">
        <!-- <form role="form" method="POST" id="form_curso" enctype="multipart/form-data"> -->
        <?php echo form_open("#", array('id'=>'form_curso', "method"=>"POST")); ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-semibold">Carrera</label>
                        <select id="carreras" name="carreras" class="form-control">
                            <option value="-1">Seleccione...</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-semibold">Materia</label>
                        <select id="materias" name="materias" class="form-control">
                            <option value="-1">Seleccione...</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-semibold">Paralelo</label>
                        <select id="paralelos" name="paralelos" class="form-control">
                            <option value="-1">Seleccione...</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" name="" id="" class="btn btn-primary">Administrar notas <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-3">
            <div class="form-group">
                <button class="btn btn-success" onclick="pdf();" target="_blank">Imprimir</button>
            </div>
        </div>          
    </div>
</div>

<!-- <form role="form" method="POST" enctype="multipart/form-data" id="form_notes"> -->

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Subir notas de los estudiantes</h5>
                <!-- <button id="ocultar">Hide</button>
                <button id="mostrar">Show</button> -->
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                </ul>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table datatable-basic table-striped">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th class="primer_bim">1º Bimistre</th>
                        <th class="segundo_bim">2º Bimistre</th>
                        <th class="tercer_bim">3º Bimistre</th>
                        <th class="cuarto_bim">4º Bimistre</th>
                        <th class="final">Nota Final</th>
                        <th class="segundo_turno">Seg. Turno</th>
                    </tr>
                </thead>
                <tbody id="curso_tbody">
                </tbody>
            </table>
        </div>
    </div>
    <!-- <div class="text-center"> -->
        <button id="btnSaveNotes" type="submit" o  class="btn btn-primary legitRipple">Guardar notas</button>
        <!-- <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Add Person</button> -->
    <!-- </div> -->
<!-- </form> -->