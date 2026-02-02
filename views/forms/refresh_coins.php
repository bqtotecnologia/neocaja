<?php 
$admitted_user_types = ['Cajero', 'Tecnologia', 'Super'];
include_once '../../utils/validate_user_type.php';

include '../../views/common/header.php';
include_once '../../models/coin_model.php';
$coin_model = new CoinModel();

$coins = $coin_model->GetNotUpdatedCoins(false);
?>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="h1 text-black">Consultar y actualizar automáticamente las tasas de las monedas del sistema</h1>
    </div>

    <div class="col-12 row justify-content-center px-4">
        <div class="x_panel col-12 row justify-content-center ">
            <div class="row col-12 justify-content-center">
                <?php if($coins === []) { ?>
                    <h2 class="col-12 text-center h2">
                        Todas las monedas están al día
                    </h2>
                    <i class="fa fa-check col-12 text-center text-success" style="font-size:80px;"></i>
                <?php } else { ?>
                    <form class="row col-12 col-lg-8 m-0 p-0 justify-content-center" method="POST" action="<?= $base_url ?>/controllers/refresh_coins.php">
                        <input type="hidden" name="allow_refresh" value="1">
                        <h2 class="col-12 text-center h2">
                            Las siguientes monedas están desactualizadas
                        </h2>
                        <table class="table table-striped table-bordered mb-5" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Tasa más reciente</th>
                                    <th class="text-center">Fecha de la tasa</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                <?php foreach($coins as $coin) {  ?>
                                    <tr class="h6">
                                        <td class="align-middle text-center"><?= $coin['name'] ?></td>
                                        <td class="align-middle text-center"><?= $coin['price'] ?></td>
                                        <td class="align-middle text-center"><?= date('d/m/Y', strtotime($coin['price_created_at'])) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
    
                        <div class="col-12 text-center mt-5">
                            <button class="btn btn-primary">
                                Actualizar automáticamente
                            </button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php include '../common/footer.php'; ?>