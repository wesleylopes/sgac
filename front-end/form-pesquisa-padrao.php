<div class="load"> <img src="assets/images/loading.gif"></div>
<div class="card-header">
    <i class="fa fa-filter"></i> FILTROS
</div>
<br>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class=" container card mb-3">
            <form method="POST">
                <div class="row">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label for="example3">
                            Data Inicial:
                        </label>
                        <div class="form-group ">
                            <input type="date" class="form-control" value="<?php echo $_POST['datai'];?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label for="example3">
                            Data Final:
                        </label>
                        <div class="form-group">
                            <input type="date" class="form-control" value="<?php echo $_POST['dataf'];?>" id="InputDataf" name="dataf" placeholder="Data final">
                        </div>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label for="example3">
                            Cidade:
                        </label>
                        <select multiple class=" form-control select2" id="cidade" name="cidade[]" multiple="cidade">
                            <?php 
                            $sql="SELECT distinct(CIDADE) as CIDADE
                                                  FROM movimento_veiculos";
                            $sql = $db->query($sql);
                            $dados = $sql->fetchAll();

                            foreach ($dados as $quantidade){                                                        
                                echo  retornaValorChecado($quantidade['CIDADE'],$_POST['cidade']); 
                            } 

                            ?>
                        </select>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label for="example3">
                            Polo:
                        </label>
                        <select multiple class=" form-control select2" id="polo" name="polo[]">
                            <?php 
                            $sql="SELECT distinct(CENTRO_RESULTADO) as CENTRO_RESULTADO
                                                  FROM movimento_veiculos";
                            $sql = $db->query($sql);
                            $dados = $sql->fetchAll();                                                    

                            foreach ($dados as $quantidade){                                                        
                                echo  retornaValorChecado($quantidade['CENTRO_RESULTADO'],$_POST['polo']); 
                            }   
                            echo "</select>";

                            ?>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label for="example4">
                            Equipe:
                        </label>
                        <select multiple class=" form-control select2" id="equipe" name="equipe[]">
                            <?php 
                                $sql="SELECT distinct(CENTRO_CUSTO) as CENTRO_CUSTO
                                                  FROM movimento_veiculos";
                                $sql = $db->query($sql);
                                $dados = $sql->fetchAll();

                                foreach ($dados as $quantidade){
                                    echo  retornaValorChecado($quantidade['CENTRO_CUSTO'],$_POST['equipe']); 
                                } 
                                ?>

                        </select>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <label for="example5">
                            Veiculo:
                        </label>
                        <select multiple class=" form-control select2" id="veiculo" name="veiculo[]">
                            <?php 
                                $sql="SELECT distinct(a.PLACA_VEICULO) as PLACA_VEICULO, b.MODELO_VEICULO as MODELO_VEICULO
                                                  FROM movimento_veiculos a
                                                  inner join veiculos b on (a.PLACA_VEICULO = b.PLACA_VEICULO)";
                                $sql = $db->query($sql);
                                $dados = $sql->fetchAll();

                                foreach ($dados as $quantidade){
                                    echo  retornaValorChecado($quantidade['PLACA_VEICULO'],$_POST['veiculo']);
                                } 

                                ?>

                        </select>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 ">
                        <label for="example5">
                            Tipo de Veiculo:
                        </label>
                        <select multiple class=" form-control select2" id="tpVeiculo" name="tpVeiculo[]">
                            <?php 
                                $sql="select DISTINCT(TIPO_VEICULO) AS TIPO_VEICULO from veiculos a";
                                $sql = $db->query($sql);
                                $dados = $sql->fetchAll();

                                foreach ($dados as $quantidade){
                                    echo  retornaValorChecado($quantidade['TIPO_VEICULO'],$_POST['tpVeiculo']); 

                                } 
                                ?>
                        </select>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 ">
                        <label for="example5">
                            Modelo Veiculo:
                        </label>
                        <select multiple class=" form-control select2" id="modeloVeiculo" name="modeloVeiculo[]">
                            <?php 
                                $sql="select DISTINCT(MODELO_VEICULO) AS MODELO_VEICULO from veiculos a";
                                $sql = $db->query($sql);
                                $dados = $sql->fetchAll();

                                foreach ($dados as $quantidade){
                                    echo  retornaValorChecado($quantidade['MODELO_VEICULO'],$_POST['modeloVeiculo']); 

                                } 
                                ?>
                        </select>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <br>
                        <label for="example5">
                            Posto:
                        </label>
                        <select multiple class=" form-control select2" id="posto" name="posto[]">

                            <?php 
                                $sql="SELECT distinct(NOME_POSTO) as NOME_POSTO
                                                  FROM movimento_veiculos";
                                $sql = $db->query($sql);
                                $dados = $sql->fetchAll();

                                foreach ($dados as $quantidade){
                                    echo  retornaValorChecado($quantidade['NOME_POSTO'],$_POST['posto']); 
                                } 

                                ?>

                        </select>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <br>
                        <label for="example5">
                            Tipo Combustivel:
                        </label>
                        <select multiple class=" form-control select2" id="tpCombustivel" name="tpCombustivel[]">
                            <?php 
                                $sql="SELECT distinct(PRODUTO) as PRODUTO FROM movimento_veiculos";
                                $sql = $db->query($sql);
                                $dados = $sql->fetchAll();

                                foreach ($dados as $quantidade){
                                    echo  retornaValorChecado($quantidade['PRODUTO'],$_POST['tpCombustivel']); 
                                } 
                                ?>
                        </select>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 float-right ">
                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar</button>
                    </div>
                </div>
        </div>
        </form>

    </div>
</div>
 <script>
    //código usando jQuery
    $(document).ready(function() {
    $('.load').hide();
    });
 </script>
