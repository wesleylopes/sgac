<div class="left main-sidebar">
  <div class="sidebar-inner leftscroll">
    <br>
    <div id="sidebar-menu">
      <ul>
        <li class="submenu">
          <a class="active" href="index.php"><i class="fa fa-fw fa-tachometer"></i><span> VISÃO GERAL </span> </a>
          <a href="#"><i class="fa fa-filter"></i> <span> FILTROS / PESQUISAR</span> <span class="menu-arrow"></span></a>
          <ul class="list-unstyled">
            <li>
              <div class="container">
                <br>

                <form method="POST">
                  <div class="form-group">
                    <label class="label" for="InputDatai">Data Inicial </label>
                    <input type="date" class="form-control" value="<?php echo $_POST['datai'];?>" id="InputDatai" name="datai" aria-describedby="emailHelp" placeholder="Data inicial">
                  </div>

                  <div class="form-group">
                    <label class="label" for="InputDataf">Data Final </label>
                    <input type="date" class="form-control" value="<?php echo $_POST['dataf'];?>" id="InputDataf" name="dataf" placeholder="Data final">
                  </div>

                  <br>
                  <button type="submit" class="btn btn-primary  btn-block"><i class="fa fa-refresh"></i> Atualizar Graficos</button>

                  <br>
                  <br>
                </form>
              </div>
            </li>
          </ul>
        </li>
        <li class="submenu">
          <a class="active2" href="anomalias.php"><i class="fa fa-fw fa-bullhorn"></i><span> PAINEL DE ANOMALIAS</span>
            <span class="badge badge-danger"> 0 </span> </a>
          <a class="active2" href="analise-unidade.php"><i class="fa fa-fw fa fa-pie-chart"></i><span> ANÁLISE POR FILIAL </span></a>
          <a class="active2" href="analise-cidade.php"><i class="fa fa-fw fa-bar-chart"></i><span> ANÁLISE POR CIDADE </span></a>
          <a class="active2" href="analise-posto.php"><i class="fa fa-fw fa fa-flag"></i><span> ANÁLISE POR POSTO </span></a>
          <a href="#"><i class="fa fa-group"></i> <span> ANÁLISE POR EQUIPE</span> <span class="menu-arrow"></span></a>
          <ul class="list-unstyled">
            <li><a href="gestao-visual.php"><i class="fa fa-fw fa-car"></i>Gestão Visual Equipe e Veiculos</a></li>
           <!-- <li><a href="veiculos.php"><i class="fa fa-fw fa-user"></i>Comparativo</a></li>-->
         
          </ul>
          <a class="active2" href="evolucao-preco.php"><i class="fa fa-fw fa-line-chart"></i><span> EVOLUÇÃO PREÇO</span></a>
          <a class="active2" href="evolucao-quantidade.php"><i class="fa fa-fw fa fa-tint"></i><span> EVOLUÇÃO QUANTIDADE</span></a>
          <a class="active2" href="importa.php"><i class="fa fa-fw fa-file-excel-o"></i><span>IMPORTAR DADOS (EXCEL)</span></a>
        </li>

        <li class="submenu">
          <a href="#"><i class="fa fa-archive"></i> <span> CADASTROS</span> <span class="menu-arrow"></span></a>
          <ul class="list-unstyled">
            <li><a href="veiculos.php"><i class="fa fa-fw fa-car"></i>Cad. de Veículos</a></li>
            <li><a href="veiculos.php"><i class="fa fa-fw fa-user"></i>Cad. de Motoristas</a></li>
            <li><a href="usuarios.php"><i class="fa fa-fw fa fa-user-circle"></i>Cad. de Usuários</a></li>
          </ul>
        </li>

        <li class="submenu">
          <a href="#"><i class="fa fa-wpforms"></i> <span> RELATÓRIOS</span> <span class="menu-arrow"></span></a>

        </li>

        <li class="submenu">
          <a href="sair.php"><i class="fa fa-sign-out"></i> <span> SAIR</span></a>
        </li>

        <div class="clearfix">
        </div>
      </ul>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
