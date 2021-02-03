<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Seleccione la carrera, materia y paralelo que tiene asignado.</h5>
    </div>
    <div class="panel-body">
        <?php echo form_open("#", array('id'=>'form_centralizador', "method"=>"POST")); ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-semibold">Carreras</label>
                        <select id="carreras" name="carreras" class="form-control">
                            <option value="-1">Seleccione...</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1">
                    <div class="form-group">
                        <label class="text-semibold">Paralelos</label>
                        <select id="paralelos" name="paralelos" class="form-control">
                            <!-- <option value="-1">Seleccione...</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="text-semibold">Bimestre</label>
                        <select id="bimestre" name="bimestre" class="form-control">
                            <option value="primer_bim">Primer Bimestre</option>
                            <option value="segundo_bim">Segundo Bimestre</option>
                            <option value="tercer_bim">Tercer Bimestre</option>
                            <option value="cuarto_bim">Cuarto Bimestre</option>
                            <option value="final">Promedio Anual</option>
                            <option value="segundo_turno">Segundo Turno</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="text-semibold">Plan</label>
                        <select id="plan" name="plan" class="form-control">
                            <!-- <option value="-1">Seleccione...</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="text-semibold">Gestión</label>
                        <select id="year" name="year" class="form-control">
                            <!-- <option value="-1">Seleccione...</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" name="" id="" class="btn btn-primary">Ver <i class="icon-arrow-right14 position-right"></i></button>
                    </div>

                </div>
            </div>
        </form>
        <div class="col-md-3">
            <div class="form-group">
                <button class="btn btn-success" onclick="pdf_cent();" target="_blank">Centralizador PDF</button>
            </div>
        </div>         
    </div>
</div>

 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Centralizador<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="reload"></a></li>
                <!-- <li><a data-action="close"></a></li> -->
            </ul>
        </div>
    </div>

    <div class="panel-body">
        <!-- Example of <code>extra mini</code> table sizing using <code>.table-xxs</code> classadded to the <code>.table</code>. All table rows have <code>32px</code> height.'; -->
    </div>

    <div class="table-responsive students">
        
    </div>
</div>


<style>
th.rotate {
  /* Something you can count on */
  height: 240px;
  white-space: nowrap;
}

th.rotate > div {
  transform: 
    /* Magic Numbers */
    translate(0px, 100px)
    /* 45 is really 360 - 45 */
    rotate(-90deg);
  width: 30px;
}
/*th.rotate > div > span {
  border-bottom: 1px solid #ccc;
  padding: 5px 10px;
}*/
</style>